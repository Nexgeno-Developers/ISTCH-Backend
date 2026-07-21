<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Payment;
use App\Payments\StripePayment;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiConnectionException;
use Tests\StripeDonationTestCase;

class StripeDonationCheckoutTest extends StripeDonationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        config([
            'services.payment.default' => 'stripe',
            'services.stripe.mode' => 'sandbox',
            'services.stripe.test.key' => 'pk_test_example',
            'services.stripe.test.secret' => 'sk_test_example',
            'custom.admin_email' => 'payments@istch.org',
            'custom.company_id' => null,
        ]);

        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1,
            'is_active' => true,
        ]);
    }

    public function test_missing_stripe_key_returns_a_controlled_unavailable_response(): void
    {
        config([
            'services.stripe.test.key' => null,
            'services.stripe.test.secret' => null,
        ]);

        // Resolving the service itself must remain safe when configuration is absent.
        $this->assertInstanceOf(StripePayment::class, app(StripePayment::class));

        $this->postJson('/api/donate', $this->validDonationPayload())
            ->assertStatus(503)
            ->assertExactJson([
                'code' => 'PAYMENT_PROVIDER_UNAVAILABLE',
                'message' => 'Payments are temporarily unavailable. Please try again later.',
            ]);

        $this->assertDatabaseHas('payments', [
            'email' => 'donor@example.com',
            'payment_status' => Payment::STATUS_FAILED,
        ]);
    }

    public function test_invalid_stripe_configuration_returns_a_controlled_unavailable_response(): void
    {
        config(['services.stripe.test.secret' => 'not-a-stripe-secret']);

        $this->postJson('/api/donate', $this->validDonationPayload())
            ->assertStatus(503)
            ->assertJsonPath('code', 'PAYMENT_PROVIDER_UNAVAILABLE')
            ->assertJsonMissing(['not-a-stripe-secret']);
    }

    public function test_donation_validation_runs_while_stripe_is_unavailable(): void
    {
        config([
            'services.stripe.test.key' => null,
            'services.stripe.test.secret' => null,
        ]);

        $this->postJson('/api/donate', array_merge($this->validDonationPayload(), [
            'email' => 'not-an-email',
            'amount' => 0,
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Validation failed.')
            ->assertJsonValidationErrors(['email', 'amount']);

        $this->assertDatabaseCount('payments', 0);
    }

    public function test_successful_mocked_checkout_creation(): void
    {
        $session = Session::constructFrom([
            'id' => 'cs_test_success',
            'url' => 'https://checkout.stripe.test/cs_test_success',
        ]);

        $stripe = Mockery::mock(StripePayment::class);
        $stripe->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturnUsing(function (Payment $payment) use ($session) {
                $payment->forceFill(['stripe_checkout_session_id' => $session->id])->save();

                return $session;
            });
        app()->instance(StripePayment::class, $stripe);

        $this->postJson('/api/donate', $this->validDonationPayload())
            ->assertCreated()
            ->assertJsonPath('data.checkout_session_id', 'cs_test_success')
            ->assertJsonPath('data.checkout_url', 'https://checkout.stripe.test/cs_test_success');

        $this->assertDatabaseHas('payments', [
            'email' => 'donor@example.com',
            'payment_status' => Payment::STATUS_PENDING,
            'stripe_checkout_session_id' => 'cs_test_success',
        ]);
    }

    public function test_stripe_api_exception_is_translated_to_a_safe_gateway_response(): void
    {
        $stripe = Mockery::mock(StripePayment::class);
        $stripe->shouldReceive('createCheckoutSession')
            ->once()
            ->andThrow(ApiConnectionException::factory('Sensitive upstream diagnostic'));
        app()->instance(StripePayment::class, $stripe);

        $this->postJson('/api/donate', $this->validDonationPayload())
            ->assertStatus(502)
            ->assertExactJson([
                'code' => 'PAYMENT_PROVIDER_ERROR',
                'message' => 'Unable to start payment right now. Please try again.',
            ])
            ->assertJsonMissing(['Sensitive upstream diagnostic']);

        $this->assertDatabaseHas('payments', [
            'email' => 'donor@example.com',
            'payment_status' => Payment::STATUS_FAILED,
        ]);
    }

    private function validDonationPayload(): array
    {
        return [
            'payment_type' => Payment::TYPE_ONE_TIME,
            'full_name' => 'Test Donor',
            'email' => 'donor@example.com',
            'country' => 'India',
            'currency' => 'USD',
            'amount' => 25,
        ];
    }
}
