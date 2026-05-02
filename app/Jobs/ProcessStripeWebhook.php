<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
            'invoice.paid' => $this->handleInvoicePaid($object),
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

    private function handleInvoicePaid(array $invoice): void
    {
        $subscriptionId = $invoice['subscription'] ?? null;
        $payment = $subscriptionId ? Payment::where('stripe_subscription_id', $subscriptionId)->first() : null;
        if (!$payment) {
            $paymentId = $invoice['subscription_details']['metadata']['payment_id']
                ?? $invoice['parent']['subscription_details']['metadata']['payment_id']
                ?? null;
            $payment = $paymentId ? Payment::find($paymentId) : null;
        }
        if (!$payment) {
            Log::warning('Stripe invoice paid webhook could not find payment.', ['subscription' => $subscriptionId]);
            return;
        }

        $payment->forceFill([
            'stripe_invoice_id' => $invoice['id'] ?? $payment->stripe_invoice_id,
            'stripe_payment_intent_id' => $invoice['payment_intent'] ?? $payment->stripe_payment_intent_id,
            'webhook_received_at' => now(),
        ])->save();
        $payment->markPaid($invoice['payment_intent'] ?? null, $invoice['id'] ?? null);
        $payment->mergeMeta(['stripe' => ['invoice_paid' => $this->eventSummary()]]);
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
