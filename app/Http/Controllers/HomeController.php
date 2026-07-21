<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Payment;
use App\Payments\StripePayment;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        abort_unless($this->validPaymentId($request), 404);

        $payment = Payment::find($request->query('payment'));

        if ($payment && $payment->stripe_checkout_session_id) {
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

        // return view('payments.success', compact('payment'));
        return redirect()->away($this->frontendPaymentUrl('success', $payment));
    }

    public function cancel(Request $request): RedirectResponse
    {
        abort_unless($this->validPaymentId($request), 404);

        $payment = Payment::find($request->query('payment'));

        if ($payment && $payment->payment_status === Payment::STATUS_PENDING) {
            $payment->forceFill(['payment_status' => Payment::STATUS_CANCELLED])->save();
            $payment->refresh();
        }

        // return view('payments.cancel', compact('payment'));
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

    private function validPaymentId(Request $request): bool
    {
        $paymentId = (string) $request->query('payment', '');

        return ctype_digit($paymentId) && (int) $paymentId > 0;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
