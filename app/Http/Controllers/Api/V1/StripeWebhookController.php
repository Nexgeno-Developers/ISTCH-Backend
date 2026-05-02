<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessStripeWebhook;
use App\Payments\StripePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, StripePayment $stripePayment): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $webhookSecret = $stripePayment->webhookSecret();

        if (blank($webhookSecret)) {
            Log::error('Stripe webhook rejected: webhook secret is not configured.');
            return response()->json(['message' => 'Webhook not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (UnexpectedValueException $e) {
            Log::warning('Stripe webhook rejected: invalid payload.', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook rejected: invalid signature.', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $eventArray = $event->toArray();
        Log::info('Stripe webhook received.', [
            'id' => $eventArray['id'] ?? null,
            'type' => $eventArray['type'] ?? null,
        ]);

        ProcessStripeWebhook::dispatchSync($eventArray);

        return response()->json(['received' => true]);
    }
}
