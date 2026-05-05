<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Invoice as StripeInvoice;

class ProcessStripeWebhook implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(private readonly array $event)
    {
    }

    public function handle(): void
    {
        $type = $this->event['type'] ?? '';
        $object = $this->event['data']['object'] ?? [];

        match ($type) {
            'checkout.session.completed' => $this->handleCheckoutSessionCompleted($object),
            'payment_intent.succeeded' => $this->handlePaymentIntentSucceeded($object),
            'payment_intent.payment_failed' => $this->handlePaymentIntentFailed($object),
            'invoice.created' => $this->handleInvoiceCreated($object),
            'invoice.paid' => $this->handleInvoicePaid($object),
            'invoice.payment_succeeded' => $this->handleInvoicePaid($object),
            'invoice_payment.paid' => $this->handleInvoicePaymentPaid($object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($object),
            default => Log::info('Unhandled Stripe webhook event.', ['type' => $type]),
        };
    }

    private function handleCheckoutSessionCompleted(array $session): void
    {
        $payment = $this->paymentFromSession($session);
        if (!$payment) {
            return;
        }

        $payment->forceFill([
            'stripe_customer_id' => $session['customer'] ?? $payment->stripe_customer_id,
            'stripe_payment_intent_id' => $session['payment_intent'] ?? $payment->stripe_payment_intent_id,
            'stripe_subscription_id' => $session['subscription'] ?? $payment->stripe_subscription_id,
            'webhook_received_at' => now(),
        ])->save();

        if (($session['payment_status'] ?? null) === 'paid') {
            $payment->markPaid($session['payment_intent'] ?? null);
        }

        $payment->mergeMeta(['stripe' => ['checkout_session_completed' => $this->eventSummary()]]);
    }

    private function handlePaymentIntentSucceeded(array $intent): void
    {
        $payment = $this->paymentFromPaymentIntent($intent);
        if (!$payment) {
            return;
        }

        $payment->forceFill(['webhook_received_at' => now()])->save();
        $payment->markPaid($intent['id'] ?? null);
        $payment->mergeMeta(['stripe' => ['payment_intent_succeeded' => $this->eventSummary()]]);
    }

    private function handlePaymentIntentFailed(array $intent): void
    {
        $payment = $this->paymentFromPaymentIntent($intent);
        if (!$payment) {
            return;
        }

        $payment->forceFill([
            'payment_status' => Payment::STATUS_FAILED,
            'stripe_payment_intent_id' => $intent['id'] ?? $payment->stripe_payment_intent_id,
            'webhook_received_at' => now(),
        ])->save();
        $payment->mergeMeta(['stripe' => ['payment_intent_failed' => $this->eventSummary()]]);
    }

    private function handleInvoiceCreated(array $invoice): void
    {
        if (($invoice['billing_reason'] ?? null) !== 'subscription_cycle') {
            return;
        }

        $invoiceId = $invoice['id'] ?? null;
        if (!$invoiceId) {
            return;
        }

        $payment = Payment::where('stripe_invoice_id', $invoiceId)->first();
        if ($payment) {
            $payment->mergeMeta(['stripe' => ['invoice_created' => $this->eventSummary()]]);
            return;
        }

        $sourcePayment = $this->paymentFromInvoice($invoice);
        if (!$sourcePayment) {
            Log::warning('Stripe invoice created webhook could not find source payment.', [
                'invoice' => $invoiceId,
                'subscription' => $this->subscriptionIdFromInvoice($invoice),
            ]);
            return;
        }

        $this->ensurePaymentGroupId($sourcePayment, $invoice);

        $payment = Payment::create($this->renewalPaymentAttributes($sourcePayment, $invoice));
        $payment->mergeMeta(['stripe' => ['invoice_created' => $this->eventSummary()]]);
    }

    private function handleInvoicePaid(array $invoice): void
    {
        $invoiceId = $invoice['id'] ?? null;
        $paymentIntentId = $this->paymentIntentIdFromInvoice($invoice);
        $payment = $invoiceId ? Payment::where('stripe_invoice_id', $invoiceId)->first() : null;

        if (!$payment) {
            $payment = $this->paymentFromInvoice($invoice);
        }

        if (!$payment) {
            Log::warning('Stripe invoice paid webhook could not find payment.', [
                'invoice' => $invoiceId,
                'subscription' => $this->subscriptionIdFromInvoice($invoice),
            ]);
            return;
        }

        $this->ensurePaymentGroupId($payment, $invoice);

        if (
            $invoiceId
            && $payment->payment_type === Payment::TYPE_MONTHLY
            && ($invoice['billing_reason'] ?? null) === 'subscription_cycle'
        ) {
            $payment = Payment::firstOrCreate(
                ['stripe_invoice_id' => $invoiceId],
                $this->renewalPaymentAttributes($payment, $invoice)
            );
        }

        $payment->forceFill([
            'stripe_invoice_id' => $invoiceId ?? $payment->stripe_invoice_id,
            'stripe_payment_intent_id' => $paymentIntentId ?? $payment->stripe_payment_intent_id,
            'webhook_received_at' => now(),
        ])->save();
        $payment->markPaid($paymentIntentId, $invoiceId);
        $payment->mergeMeta(['stripe' => ['invoice_paid' => $this->eventSummary()]]);
    }

    private function handleInvoicePaymentPaid(array $invoicePayment): void
    {
        $invoiceId = isset($invoicePayment['invoice']) && is_string($invoicePayment['invoice'])
            ? $invoicePayment['invoice']
            : null;
        $paymentIntentId = $this->paymentIntentIdFromInvoicePayment($invoicePayment);

        $payment = $invoiceId ? Payment::where('stripe_invoice_id', $invoiceId)->first() : null;

        if (!$payment && $paymentIntentId) {
            $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();
        }

        if (!$payment) {
            $invoice = $this->retrieveStripeInvoice($invoiceId);
            if ($invoice) {
                $this->handleInvoicePaid($invoice);
                return;
            }

            Log::warning('Stripe invoice payment paid webhook could not find existing payment.', [
                'invoice' => $invoiceId,
                'payment_intent' => $paymentIntentId,
            ]);
            return;
        }

        $payment->forceFill([
            'stripe_invoice_id' => $invoiceId ?? $payment->stripe_invoice_id,
            'stripe_payment_intent_id' => $paymentIntentId ?? $payment->stripe_payment_intent_id,
            'webhook_received_at' => now(),
        ])->save();
        $payment->markPaid($paymentIntentId, $invoiceId);
        $payment->mergeMeta(['stripe' => ['invoice_payment_paid' => $this->eventSummary()]]);
    }

    private function handleSubscriptionDeleted(array $subscription): void
    {
        $payment = Payment::where('stripe_subscription_id', $subscription['id'] ?? null)->first();
        if (!$payment && !empty($subscription['metadata']['payment_id'])) {
            $payment = Payment::find($subscription['metadata']['payment_id']);
        }
        if (!$payment) {
            Log::warning('Stripe subscription deleted webhook could not find payment.', ['subscription' => $subscription['id'] ?? null]);
            return;
        }

        $payment->forceFill([
            'payment_status' => Payment::STATUS_CANCELLED,
            'webhook_received_at' => now(),
        ])->save();
        $payment->mergeMeta(['stripe' => ['subscription_deleted' => $this->eventSummary()]]);
    }

    private function paymentFromSession(array $session): ?Payment
    {
        $payment = Payment::where('stripe_checkout_session_id', $session['id'] ?? null)->first();
        if (!$payment && !empty($session['metadata']['payment_id'])) {
            $payment = Payment::find($session['metadata']['payment_id']);
        }

        if (!$payment) {
            Log::warning('Stripe checkout webhook could not find payment.', ['session' => $session['id'] ?? null]);
        }

        return $payment;
    }

    private function paymentFromPaymentIntent(array $intent): ?Payment
    {
        $payment = Payment::where('stripe_payment_intent_id', $intent['id'] ?? null)->first();
        if (!$payment && !empty($intent['metadata']['payment_id'])) {
            $payment = Payment::find($intent['metadata']['payment_id']);
        }

        if (!$payment) {
            Log::warning('Stripe payment intent webhook could not find payment.', ['payment_intent' => $intent['id'] ?? null]);
        }

        return $payment;
    }

    private function paymentFromInvoice(array $invoice): ?Payment
    {
        $subscriptionId = $this->subscriptionIdFromInvoice($invoice);
        $payment = $subscriptionId
            ? Payment::where('stripe_subscription_id', $subscriptionId)->orderBy('id')->first()
            : null;

        if (!$payment) {
            $paymentId = $this->metadataValueFromInvoice($invoice, 'payment_id');
            $payment = $paymentId ? Payment::find($paymentId) : null;
        }

        return $payment;
    }

    private function subscriptionIdFromInvoice(array $invoice): ?string
    {
        return $invoice['subscription']
            ?? $invoice['parent']['subscription_details']['subscription']
            ?? null;
    }

    private function ensurePaymentGroupId(Payment $payment, array $invoice): void
    {
        if ($payment->payment_group_id) {
            return;
        }

        $payment->forceFill([
            'payment_group_id' => $this->metadataValueFromInvoice($invoice, 'payment_group_id') ?? (string) Str::uuid(),
        ])->save();
    }

    private function metadataValueFromInvoice(array $invoice, string $key): ?string
    {
        $value = $invoice['metadata'][$key]
            ?? $invoice['subscription_details']['metadata'][$key]
            ?? $invoice['parent']['subscription_details']['metadata'][$key]
            ?? null;

        return is_scalar($value) && (string) $value !== '' ? (string) $value : null;
    }

    private function renewalPaymentAttributes(Payment $payment, array $invoice): array
    {
        $amount = $this->amountFromInvoice($invoice, $payment);
        $exchangeRate = (float) $payment->exchange_rate > 0 ? (float) $payment->exchange_rate : 1;
        $paymentIntentId = $this->paymentIntentIdFromInvoice($invoice);

        return [
            'full_name' => $payment->full_name,
            'email' => $payment->email,
            'phone' => $payment->phone,
            'country' => $payment->country,
            'payment_group_id' => $payment->payment_group_id,
            'payment_type' => Payment::TYPE_MONTHLY,
            'currency' => strtoupper($invoice['currency'] ?? $payment->currency),
            'amount' => $amount,
            'exchange_rate' => $exchangeRate,
            'usd_amount' => round($amount / $exchangeRate, 2),
            'payment_status' => Payment::STATUS_PENDING,
            'stripe_customer_id' => $invoice['customer'] ?? $payment->stripe_customer_id,
            'stripe_subscription_id' => $this->subscriptionIdFromInvoice($invoice) ?? $payment->stripe_subscription_id,
            'stripe_invoice_id' => $invoice['id'] ?? null,
            'stripe_payment_intent_id' => $paymentIntentId,
            'webhook_received_at' => now(),
            'meta' => [
                'payment_provider' => 'stripe',
                'stripe_mode' => $payment->meta['stripe_mode'] ?? config('services.stripe.mode', 'sandbox'),
                'source_payment_id' => $payment->id,
                'invoice_billing_reason' => $invoice['billing_reason'] ?? null,
            ],
        ];
    }

    private function amountFromInvoice(array $invoice, Payment $payment): float
    {
        $amount = $invoice['amount_paid'] ?? null;
        if (!is_numeric($amount) || (float) $amount <= 0) {
            $amount = $invoice['amount_due'] ?? null;
        }
        if (!is_numeric($amount)) {
            return (float) $payment->amount;
        }

        $currency = strtoupper($invoice['currency'] ?? $payment->currency);
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

        return in_array($currency, $zeroDecimalCurrencies, true)
            ? (float) $amount
            : round(((float) $amount) / 100, 2);
    }

    private function paymentIntentIdFromInvoice(array $invoice): ?string
    {
        $value = $invoice['payment_intent']
            ?? $invoice['payment']['payment_intent']
            ?? $invoice['payments']['data'][0]['payment']['payment_intent']
            ?? null;

        return is_scalar($value) && (string) $value !== '' ? (string) $value : null;
    }

    private function paymentIntentIdFromInvoicePayment(array $invoicePayment): ?string
    {
        $value = $invoicePayment['payment']['payment_intent']
            ?? $invoicePayment['payment_intent']
            ?? null;

        return is_scalar($value) && (string) $value !== '' ? (string) $value : null;
    }

    private function retrieveStripeInvoice(?string $invoiceId): ?array
    {
        if (!$invoiceId) {
            return null;
        }

        try {
            return StripeInvoice::retrieve($invoiceId)->toArray();
        } catch (\Throwable $e) {
            Log::warning('Unable to retrieve Stripe invoice for webhook recovery.', [
                'invoice' => $invoiceId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function eventSummary(): array
    {
        return [
            'id' => $this->event['id'] ?? null,
            'type' => $this->event['type'] ?? null,
            'created' => $this->event['created'] ?? null,
            'received_at' => now()->toIso8601String(),
        ];
    }
}
