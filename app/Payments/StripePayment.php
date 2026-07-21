<?php

namespace App\Payments;

use App\Models\Payment;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripePayment
{
    private ?StripeClient $client = null;

    public function secretKey(): string
    {
        $mode = $this->mode();

        $secret = trim($mode === 'live'
            ? (string) config('services.stripe.live.secret')
            : (string) config('services.stripe.test.secret'));

        $key = trim($mode === 'live'
            ? (string) config('services.stripe.live.key')
            : (string) config('services.stripe.test.key'));

        if (blank($secret) && str_starts_with($key, 'sk_')) {
            return $key;
        }

        if (blank($secret)) {
            throw new StripeConfigurationException('secret_key_missing');
        }

        $expectedPrefix = $mode === 'live' ? 'sk_live_' : 'sk_test_';
        if (! str_starts_with($secret, $expectedPrefix) || ! preg_match('/^sk_(?:test|live)_[A-Za-z0-9_]+$/', $secret)) {
            throw new StripeConfigurationException('secret_key_invalid');
        }

        return $secret;
    }

    public function webhookSecret(): string
    {
        $mode = $this->mode();

        return $mode === 'live'
            ? (string) config('services.stripe.live.webhook_secret')
            : (string) config('services.stripe.test.webhook_secret');
    }

    public function createCheckoutSession(Payment $payment): Session
    {
        $amountInSmallestUnit = $this->toSmallestUnit((float) $payment->amount, $payment->currency);
        $successUrl = route('payment.success.page', ['payment' => $payment->id]);
        $cancelUrl = route('payment.cancel.page', ['payment' => $payment->id]);

        $lineItem = [
            'price_data' => [
                'currency' => strtolower($payment->currency),
                'product_data' => [
                    'name' => $payment->payment_type === Payment::TYPE_MONTHLY ? 'Monthly Donation' : 'One-time Donation',
                    'description' => 'Donation from ' . $payment->full_name,
                ],
                'unit_amount' => $amountInSmallestUnit,
            ],
            'quantity' => 1,
        ];

        if ($payment->payment_type === Payment::TYPE_MONTHLY) {
            $lineItem['price_data']['recurring'] = ['interval' => 'month'];
        }

        $params = [
            'mode' => $payment->payment_type === Payment::TYPE_MONTHLY ? 'subscription' : 'payment',
            'payment_method_types' => ['card'],
            'customer_email' => $payment->email,
            'line_items' => [$lineItem],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'payment_id' => (string) $payment->id,
                'payment_group_id' => (string) $payment->payment_group_id,
                'payment_type' => $payment->payment_type,
            ],
        ];

        if ($payment->payment_type === Payment::TYPE_ONE_TIME) {
            $params['payment_intent_data'] = ['metadata' => [
                'payment_id' => (string) $payment->id,
                'payment_group_id' => (string) $payment->payment_group_id,
            ]];
        }

        if ($payment->payment_type === Payment::TYPE_MONTHLY) {
            $params['subscription_data'] = ['metadata' => [
                'payment_id' => (string) $payment->id,
                'payment_group_id' => (string) $payment->payment_group_id,
            ]];
        }

        $session = $this->client()->checkout->sessions->create($params);

        $payment->forceFill([
            'stripe_checkout_session_id' => $session->id,
            'stripe_customer_id' => is_string($session->customer ?? null) ? $session->customer : $payment->stripe_customer_id,
            'stripe_payment_intent_id' => is_string($session->payment_intent ?? null) ? $session->payment_intent : $payment->stripe_payment_intent_id,
            'stripe_subscription_id' => is_string($session->subscription ?? null) ? $session->subscription : $payment->stripe_subscription_id,
        ])->save();

        return $session;
    }

    public function retrieveCheckoutSession(string $sessionId): Session
    {
        return $this->client()->checkout->sessions->retrieve($sessionId, []);
    }

    /**
     * Configuration metadata that is safe to include in application logs.
     * Secret and publishable key values must never be returned here.
     *
     * @return array<string, bool|string>
     */
    public function safeConfigurationContext(): array
    {
        $mode = strtolower(trim((string) config('services.stripe.mode', 'sandbox')));
        $secret = $mode === 'live'
            ? config('services.stripe.live.secret')
            : config('services.stripe.test.secret');
        $key = $mode === 'live'
            ? config('services.stripe.live.key')
            : config('services.stripe.test.key');

        return [
            'mode' => $mode,
            'secret_configured' => filled(trim((string) $secret)),
            'publishable_key_configured' => filled(trim((string) $key)),
        ];
    }

    public function syncPaymentFromCheckoutSession(Payment $payment, Session $session, string $source = 'stripe_checkout'): string
    {
        $payment->forceFill([
            'stripe_customer_id' => is_string($session->customer ?? null) ? $session->customer : $payment->stripe_customer_id,
            'stripe_payment_intent_id' => is_string($session->payment_intent ?? null) ? $session->payment_intent : $payment->stripe_payment_intent_id,
            'stripe_subscription_id' => is_string($session->subscription ?? null) ? $session->subscription : $payment->stripe_subscription_id,
        ])->save();

        if (($session->payment_status ?? null) === 'paid') {
            $payment->markPaid(is_string($session->payment_intent ?? null) ? $session->payment_intent : null);
            $payment->mergeMeta([
                'stripe' => [
                    $source => [
                        'session_id' => $session->id,
                        'session_status' => $session->status ?? null,
                        'payment_status' => $session->payment_status ?? null,
                        'checked_at' => now()->toIso8601String(),
                    ],
                ],
            ]);

            return Payment::STATUS_PAID;
        }

        if (($session->status ?? null) === 'expired') {
            $payment->forceFill(['payment_status' => Payment::STATUS_CANCELLED])->save();
            $payment->mergeMeta([
                'stripe' => [
                    $source => [
                        'session_id' => $session->id,
                        'session_status' => $session->status,
                        'payment_status' => $session->payment_status ?? null,
                        'checked_at' => now()->toIso8601String(),
                    ],
                ],
            ]);

            return Payment::STATUS_CANCELLED;
        }

        return $payment->payment_status;
    }

    public function reconcilePendingPayments(?int $limit = null, ?int $olderThanDays = null): array
    {
        $query = Payment::query()
            ->where('payment_status', Payment::STATUS_PENDING)
            ->whereNotNull('stripe_checkout_session_id')
            ->orderBy('id');

        if ($olderThanDays !== null && $olderThanDays > 0) {
            $query->where('created_at', '<=', now()->subDays($olderThanDays));
        }

        if ($limit !== null && $limit > 0) {
            $query->limit($limit);
        }

        $summary = [
            'checked' => 0,
            'paid' => 0,
            'cancelled' => 0,
            'pending' => 0,
            'errors' => [],
        ];

        foreach ($query->get() as $payment) {
            $summary['checked']++;

            try {
                $session = $this->retrieveCheckoutSession($payment->stripe_checkout_session_id);
                $status = $this->syncPaymentFromCheckoutSession($payment, $session, 'pending_reconcile');

                if ($status === Payment::STATUS_PAID) {
                    $summary['paid']++;
                } elseif ($status === Payment::STATUS_CANCELLED) {
                    $summary['cancelled']++;
                } else {
                    $summary['pending']++;
                }
            } catch (\Throwable $e) {
                $summary['errors'][] = [
                    'payment_id' => $payment->id,
                    'session_id' => $payment->stripe_checkout_session_id,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $summary;
    }

    public function reconcileOldPendingPayments(int $olderThanDays = 7, ?int $limit = null): array
    {
        return $this->reconcilePendingPayments($limit, $olderThanDays);
    }

    private function toSmallestUnit(float $amount, string $currency): int
    {
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

        return in_array(strtoupper($currency), $zeroDecimalCurrencies, true)
            ? (int) round($amount)
            : (int) round($amount * 100);
    }

    private function client(): StripeClient
    {
        return $this->client ??= new StripeClient($this->secretKey());
    }

    private function mode(): string
    {
        $mode = strtolower(trim((string) config('services.stripe.mode', 'sandbox')));

        if (! in_array($mode, ['sandbox', 'live'], true)) {
            throw new StripeConfigurationException('mode_invalid');
        }

        return $mode;
    }
}
