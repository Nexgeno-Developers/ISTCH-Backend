<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Payment;
use App\Payments\StripePayment;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function currencies(CurrencyService $currencyService): JsonResponse
    {
        return response()->json([
            'data' => $currencyService->activeCurrencies()->map(fn (Currency $currency) => [
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'preset_amounts' => $currency->preset_amounts ?? [],
            ])->values(),
        ]);
    }

    public function donate(Request $request, CurrencyService $currencyService, StripePayment $stripePayment): JsonResponse
    {
        if (config('services.payment.default') !== 'stripe') {
            return response()->json(['message' => 'Active payment provider is not configured.'], 500);
        }

        $activeCurrencyCodes = Currency::where('is_active', true)->pluck('code')->toArray();

        $validator = Validator::make($request->all(), [
            'payment_type' => ['required', Rule::in([Payment::TYPE_ONE_TIME, Payment::TYPE_MONTHLY])],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'currency' => ['required', Rule::in($activeCurrencyCodes)],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999.99'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $currency = Currency::where('code', $validated['currency'])
            ->where('is_active', true)
            ->firstOrFail();

        $amount = round((float) $validated['amount'], 2);
        $minimum = $this->minimumAmount();
        if ($amount < $minimum) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'amount' => ['Minimum donation amount is ' . $currency->symbol . number_format($minimum, 2) . '.'],
                ],
            ], 422);
        }

        try {
            $payment = DB::transaction(function () use ($validated, $amount, $currency, $currencyService, $stripePayment) {
                $payment = Payment::create([
                    'full_name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'country' => $validated['country'],
                    'payment_type' => $validated['payment_type'],
                    'currency' => $currency->code,
                    'amount' => $amount,
                    'exchange_rate' => $currency->exchange_rate,
                    'usd_amount' => $currencyService->calculateUsdAmount($amount, $currency),
                    'payment_status' => Payment::STATUS_PENDING,
                    'meta' => [
                        'payment_provider' => 'stripe',
                        'stripe_mode' => config('services.stripe.mode', 'sandbox'),
                        'user_agent' => request()->userAgent(),
                        'ip' => request()->ip(),
                    ],
                ]);

                $session = $stripePayment->createCheckoutSession($payment);
                $payment->setAttribute('checkout_url', $session->url);

                return $payment;
            });

            return response()->json([
                'message' => 'Checkout session created.',
                'data' => [
                    'payment_id' => $payment->id,
                    'payment_status' => $payment->payment_status,
                    'checkout_session_id' => $payment->stripe_checkout_session_id,
                    'checkout_url' => $payment->getAttribute('checkout_url'),
                ],
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout creation failed: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => 'Unable to start payment right now. Please try again.',
            ], 500);
        }
    }

    public function success(Request $request, StripePayment $stripePayment): JsonResponse
    {
        $payment = Payment::find($request->query('payment'));

        if (! $payment) {
            return response()->json(['message' => 'Payment not found.'], 404);
        }

        if ($payment->stripe_checkout_session_id) {
            try {
                $session = $stripePayment->retrieveCheckoutSession($payment->stripe_checkout_session_id);
                $stripePayment->syncPaymentFromCheckoutSession($payment, $session, 'success_page_verified');
                $payment->refresh();
            } catch (\Throwable $e) {
                Log::warning('Unable to verify Stripe Checkout Session on success API: ' . $e->getMessage(), [
                    'payment_id' => $payment->id,
                    'session_id' => $payment->stripe_checkout_session_id,
                ]);
            }
        }

        return response()->json([
            'message' => 'Payment success callback received.',
            'data' => $this->paymentData($payment),
        ]);
    }

    public function cancel(Request $request): JsonResponse
    {
        $payment = Payment::find($request->query('payment'));

        if (! $payment) {
            return response()->json(['message' => 'Payment not found.'], 404);
        }

        if ($payment->payment_status === Payment::STATUS_PENDING) {
            $payment->forceFill(['payment_status' => Payment::STATUS_CANCELLED])->save();
            $payment->refresh();
        }

        return response()->json([
            'message' => 'Payment cancel callback received.',
            'data' => $this->paymentData($payment),
        ]);
    }

    private function minimumAmount(): float
    {
        return 1;
    }

    private function paymentData(Payment $payment): array
    {
        return [
            'id' => $payment->id,
            'payment_status' => $payment->payment_status,
            'payment_type' => $payment->payment_type,
            'currency' => $payment->currency,
            'amount' => $payment->amount,
            'usd_amount' => $payment->usd_amount,
            'stripe_checkout_session_id' => $payment->stripe_checkout_session_id,
            'stripe_payment_intent_id' => $payment->stripe_payment_intent_id,
            'stripe_subscription_id' => $payment->stripe_subscription_id,
            'paid_at' => optional($payment->paid_at)->toIso8601String(),
        ];
    }
}
