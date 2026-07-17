<?php

use App\Http\Middleware\TrackVisitors;

beforeEach(function () {
    $this->withoutMiddleware(TrackVisitors::class);

    config([
        'services.stripe.mode' => 'sandbox',
        'services.stripe.test.key' => 'pk_test_webhook',
        'services.stripe.test.secret' => 'sk_test_webhook',
        'services.stripe.test.webhook_secret' => 'whsec_test_webhook',
    ]);
});

it('accepts a correctly signed Stripe webhook', function () {
    $payload = json_encode([
        'id' => 'evt_test_webhook',
        'object' => 'event',
        'type' => 'test.webhook',
        'data' => ['object' => ['id' => 'obj_test_webhook']],
    ], JSON_THROW_ON_ERROR);

    $response = postStripeWebhook($this, $payload, stripeSignature($payload, 'whsec_test_webhook'));

    $response->assertOk()
        ->assertExactJson(['received' => true]);
});

it('rejects a Stripe webhook with an invalid signature', function () {
    $payload = json_encode([
        'id' => 'evt_invalid_signature',
        'object' => 'event',
        'type' => 'test.webhook',
        'data' => ['object' => []],
    ], JSON_THROW_ON_ERROR);

    $response = postStripeWebhook($this, $payload, stripeSignature($payload, 'wrong_secret'));

    $response->assertBadRequest()
        ->assertExactJson(['message' => 'Invalid signature']);
});

it('rejects Stripe webhooks when the webhook secret is not configured', function () {
    config(['services.stripe.test.webhook_secret' => '']);

    $payload = json_encode([
        'id' => 'evt_missing_secret',
        'object' => 'event',
        'type' => 'test.webhook',
        'data' => ['object' => []],
    ], JSON_THROW_ON_ERROR);

    $response = postStripeWebhook($this, $payload, '');

    $response->assertStatus(500)
        ->assertExactJson(['message' => 'Webhook not configured']);
});

function stripeSignature(string $payload, string $secret): string
{
    $timestamp = time();
    $signature = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

    return "t={$timestamp},v1={$signature}";
}

function postStripeWebhook($test, string $payload, string $signature)
{
    return $test->call(
        'POST',
        '/api/v1/stripe/webhook',
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => $signature,
        ],
        $payload,
    );
}
