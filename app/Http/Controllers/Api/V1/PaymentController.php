<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\AdminMailHelper;
use App\Http\Controllers\Controller;
use App\Mail\FormSubmissionMail;
use App\Models\Currency;
use App\Models\Form;
use App\Models\Payment;
use App\Payments\StripeConfigurationException;
use App\Payments\StripePayment;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;

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
        $activeCurrencyCodes = Currency::where('is_active', true)->pluck('code')->toArray();

        $validator = Validator::make($request->all(), [
            'payment_type' => ['required', Rule::in([Payment::TYPE_ONE_TIME, Payment::TYPE_MONTHLY])],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'country' => ['nullable', 'string', 'max:100'],
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
                    'amount' => ['Minimum donation amount is '.$currency->symbol.number_format($minimum, 2).'.'],
                ],
            ], 422);
        }

        if (config('services.payment.default') !== 'stripe') {
            Log::error('Payment provider is unavailable.', [
                'configured_provider' => config('services.payment.default'),
            ]);

            return $this->providerUnavailableResponse();
        }

        $payment = null;
        $donationForm = null;

        try {
            // Persist the donation before contacting Stripe so the submission is
            // retained even if the payment provider is temporarily unavailable.
            $payment = Payment::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'country' => $validated['country'] ?? 'Not provided',
                'payment_group_id' => (string) Str::uuid(),
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
            $donationForm = $this->createDonationForm($payment);

            $session = $stripePayment->createCheckoutSession($payment);
            $payment->setAttribute('checkout_url', $session->url);
            $this->syncDonationForm($donationForm, $payment);

            $this->notifyAdmin($payment);

            return response()->json([
                'message' => 'Checkout session created.',
                'data' => [
                    'payment_id' => $payment->id,
                    'payment_group_id' => $payment->payment_group_id,
                    'payment_status' => $payment->payment_status,
                    'form_submission_id' => $donationForm->id,
                    'checkout_session_id' => $payment->stripe_checkout_session_id,
                    'checkout_url' => $payment->getAttribute('checkout_url'),
                ],
            ], 201);
        } catch (StripeConfigurationException|AuthenticationException $e) {
            Log::critical('Stripe checkout configuration is unavailable.', array_merge(
                $stripePayment->safeConfigurationContext(),
                [
                    'reason' => $e instanceof StripeConfigurationException ? $e->reason : 'authentication_failed',
                    'exception_class' => $e::class,
                ],
            ));

            $this->markCheckoutFailed($payment, $donationForm, 'provider_configuration');

            return $this->providerUnavailableResponse();
        } catch (ApiErrorException $e) {
            Log::error('Stripe API rejected checkout creation.', [
                'exception_class' => $e::class,
                'stripe_code' => $e->getStripeCode(),
                'stripe_request_id' => $e->getRequestId(),
                'http_status' => $e->getHttpStatus(),
                'payment_id' => $payment?->id,
            ]);

            $this->markCheckoutFailed($payment, $donationForm, 'stripe_api_error');

            return response()->json([
                'code' => 'PAYMENT_PROVIDER_ERROR',
                'message' => 'Unable to start payment right now. Please try again.',
            ], 502);
        } catch (\Throwable $e) {
            Log::error('Checkout creation failed.', [
                'exception_class' => $e::class,
                'payment_id' => $payment?->id,
                'exception' => $e,
            ]);

            $this->markCheckoutFailed($payment, $donationForm, 'application_error');

            return response()->json([
                'code' => 'PAYMENT_CREATION_FAILED',
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
            } catch (StripeConfigurationException|AuthenticationException $e) {
                Log::critical('Stripe payment verification configuration is unavailable.', array_merge(
                    $stripePayment->safeConfigurationContext(),
                    [
                        'reason' => $e instanceof StripeConfigurationException ? $e->reason : 'authentication_failed',
                        'exception_class' => $e::class,
                        'payment_id' => $payment->id,
                    ],
                ));

                return $this->providerUnavailableResponse();
            } catch (ApiErrorException $e) {
                Log::error('Stripe API payment verification failed.', [
                    'exception_class' => $e::class,
                    'stripe_code' => $e->getStripeCode(),
                    'stripe_request_id' => $e->getRequestId(),
                    'http_status' => $e->getHttpStatus(),
                    'payment_id' => $payment->id,
                ]);

                return response()->json([
                    'code' => 'PAYMENT_PROVIDER_ERROR',
                    'message' => 'Unable to verify payment right now. Please try again.',
                ], 502);
            } catch (\Throwable $e) {
                Log::warning('Unable to verify Stripe Checkout Session on success API: '.$e->getMessage(), [
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

    private function providerUnavailableResponse(): JsonResponse
    {
        return response()->json([
            'code' => 'PAYMENT_PROVIDER_UNAVAILABLE',
            'message' => 'Payments are temporarily unavailable. Please try again later.',
        ], 503);
    }

    private function markCheckoutFailed(?Payment $payment, ?Form $form, string $reason): void
    {
        if (! $payment?->exists) {
            return;
        }

        $payment->forceFill(['payment_status' => Payment::STATUS_FAILED])->save();
        $payment->mergeMeta([
            'checkout_error' => [
                'reason' => $reason,
                'failed_at' => now()->toIso8601String(),
            ],
        ]);
        $this->syncDonationForm($form, $payment);
        $this->notifyAdmin($payment->fresh());
    }

    private function createDonationForm(Payment $payment): Form
    {
        return Form::create([
            'form_name' => 'donation',
            'name' => $payment->full_name,
            'email' => $payment->email,
            'phone' => $payment->phone,
            'form_data' => $this->donationFormData($payment),
            'ip' => request()->ip(),
            'company_id' => config('custom.company_id') ?? 1,
        ]);
    }

    private function syncDonationForm(?Form $form, Payment $payment): void
    {
        if (! $form) {
            return;
        }

        $form->forceFill(['form_data' => $this->donationFormData($payment)])->save();
    }

    private function donationFormData(Payment $payment): array
    {
        return [
            'full_name' => $payment->full_name,
            'country' => $payment->country,
            'payment_id' => $payment->id,
            'payment_group_id' => $payment->payment_group_id,
            'payment_type' => $payment->payment_type,
            'currency' => $payment->currency,
            'amount' => $payment->amount,
            'usd_amount' => $payment->usd_amount,
            'payment_status' => $payment->payment_status,
            'stripe_checkout_session_id' => $payment->stripe_checkout_session_id,
        ];
    }

    private function notifyAdmin(Payment $payment): void
    {
        AdminMailHelper::send(new FormSubmissionMail('donation', [
            'full_name' => $payment->full_name,
            'email' => $payment->email,
            'phone' => $payment->phone,
            'country' => $payment->country,
            'payment_type' => $payment->payment_type,
            'currency' => $payment->currency,
            'amount' => $payment->amount,
            'usd_amount' => $payment->usd_amount,
            'payment_status' => $payment->payment_status,
            'payment_group_id' => $payment->payment_group_id,
        ]));
    }

    private function paymentData(Payment $payment): array
    {
        return [
            'id' => $payment->id,
            'payment_status' => $payment->payment_status,
            'payment_type' => $payment->payment_type,
            'payment_group_id' => $payment->payment_group_id,
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
