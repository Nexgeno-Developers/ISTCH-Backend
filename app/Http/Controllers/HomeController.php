<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Payment;
use App\Payments\StripePayment;
use App\Services\CurrencyService;
use App\Services\PaymentCompletionMailService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(CurrencyService $currencyService): View
    {
        $currencies = $currencyService->activeCurrencies();

        return view('payments.donate', [
            'currencies' => $currencies,
            'currencyConfig' => $currencies->mapWithKeys(fn (Currency $currency) => [
                $currency->code => [
                    'symbol' => $currency->symbol,
                    'preset_amounts' => $currency->preset_amounts ?? [],
                ],
            ]),
        ]);
    }

    public function success(Request $request, StripePayment $stripePayment): RedirectResponse
    {
        $payment = $this->resolvePaymentFromRequest($request, 'success');
        abort_unless($payment, 404);

        if ($payment->stripe_checkout_session_id) {
            try {
                $session = $stripePayment->retrieveCheckoutSession($payment->stripe_checkout_session_id);
                $stripePayment->syncPaymentFromCheckoutSession($payment, $session, 'frontend_success_page');
                $payment->refresh();
            } catch (\Throwable $e) {
                Log::warning('Unable to verify Stripe Checkout Session on frontend success page: ' . $e->getMessage(), [
                    'payment_id' => $payment->id,
                    'session_id' => $payment->stripe_checkout_session_id,
                ]);
            }
        }

        return redirect()->away($this->frontendPaymentUrl('success', $payment));
    }

    public function cancel(Request $request): RedirectResponse
    {
        $payment = $this->resolvePaymentFromRequest($request, 'cancel');
        abort_unless($payment, 404);

        return redirect()->away($this->frontendPaymentUrl('cancel', $payment));
    }

    private function frontendPaymentUrl(string $statusPage, ?Payment $payment): string
    {
        $frontendUrl = rtrim((string) config('custom.frontend_url'), '/');

        if ($frontendUrl === '') {
            $frontendUrl = rtrim((string) config('app.url'), '/');
        }

        $payload = [
            'status_page' => $statusPage,
            'payment_id' => $payment?->id,
            'payment_status' => $payment?->payment_status,
            'payment_type' => $payment?->payment_type,
            'payment_group_id' => $payment?->payment_group_id,
            'currency' => $payment?->currency,
            'amount' => $payment?->amount,
            'usd_amount' => $payment?->usd_amount,
            'stripe_checkout_session_id' => $payment?->stripe_checkout_session_id,
            'stripe_payment_intent_id' => $payment?->stripe_payment_intent_id,
            'stripe_subscription_id' => $payment?->stripe_subscription_id,
            'paid_at' => $payment?->paid_at?->toIso8601String(),
        ];

        $payload = array_filter($payload, fn ($value) => $value !== null && $value !== '');
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));

        return $frontendUrl . '/payment/' . $statusPage . '?' . http_build_query([
            'data' => $encodedPayload,
        ]);
    }

    private function resolvePaymentFromRequest(Request $request, ?string $expectedStatusPage = null): ?Payment
    {
        $sessionId = trim((string) $request->query('session_id', ''));
        if ($sessionId !== '') {
            return Payment::where('stripe_checkout_session_id', $sessionId)->first();
        }

        $token = trim((string) $request->query('token', ''));
        if ($token === '') {
            return null;
        }

        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (DecryptException|\JsonException) {
            return null;
        }

        if (! is_array($payload)) {
            return null;
        }

        $paymentId = $payload['payment_id'] ?? null;
        $paymentGroupId = $payload['payment_group_id'] ?? null;
        $statusPage = $payload['status_page'] ?? null;

        if ($expectedStatusPage !== null && $statusPage !== $expectedStatusPage) {
            return null;
        }

        if (! is_string($paymentGroupId) || trim($paymentGroupId) === '') {
            return null;
        }

        if (! is_int($paymentId) && ! (is_string($paymentId) && ctype_digit($paymentId))) {
            return null;
        }

        return Payment::query()
            ->whereKey((int) $paymentId)
            ->where('payment_group_id', $paymentGroupId)
            ->first();
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}

