<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Payment;
use App\Payments\StripePayment;
use App\Services\CurrencyService;
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

    public function success(Request $request, StripePayment $stripePayment): View
    {
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

        return view('payments.success', compact('payment'));
    }

    public function cancel(Request $request): View
    {
        $payment = Payment::find($request->query('payment'));

        if ($payment && $payment->payment_status === Payment::STATUS_PENDING) {
            $payment->forceFill(['payment_status' => Payment::STATUS_CANCELLED])->save();
            $payment->refresh();
        }

        return view('payments.cancel', compact('payment'));
    }
}
