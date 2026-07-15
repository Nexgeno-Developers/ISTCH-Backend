<?php

use App\Helpers\AdminMailHelper;
use App\Models\Currency;
use App\Models\Form;
use App\Models\Payment;
use App\Payments\StripePayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->mailRecipient = trim((string) env('MAIL_TEST_RECIPIENT', config('custom.admin_email')));

    if (! filter_var($this->mailRecipient, FILTER_VALIDATE_EMAIL)) {
        $this->fail('Set MAIL_TEST_RECIPIENT or MAIL_ADMIN_ADDRESS to a valid inbox address.');
    }

    config([
        'custom.admin_email' => $this->mailRecipient,
        'custom.company_id' => null,
        'mail.default' => 'smtp',
        'services.payment.default' => 'stripe',
    ]);

    Mail::getFacadeRoot()->purge('smtp');
});

it('stores a contact submission and emails the company enquiry address', function () {
    Event::fake([MessageSent::class]);

    $testConnection = DB::getDefaultConnection();
    $integrationDatabase = trim((string) env('DB_INTEGRATION_DATABASE'));
    $integrationConnection = config('database.connections.mysql');
    $integrationConnection['database'] = $integrationDatabase;

    config(['database.connections.mysql_integration' => $integrationConnection]);
    DB::purge('mysql_integration');
    DB::setDefaultConnection('mysql_integration');

    try {
        if ($integrationDatabase === '' || ! Schema::hasTable('forms')) {
            $this->fail('DB_INTEGRATION_DATABASE must point to the MySQL database containing the forms table.');
        }

        $companyId = (int) (env('COMPANY_ID') ?: 1);
        config(['custom.company_id' => $companyId]);
        $expectedRecipient = AdminMailHelper::recipients($companyId)[0] ?? null;

        if (! $expectedRecipient) {
            $this->fail('No deliverable contact email recipient is configured.');
        }

        $response = $this
            ->withHeader('User-Agent', 'Mozilla/5.0')
            ->postJson('/api/forms/contact', [
                'full_name' => 'John Doe',
                'email' => 'john@example.com',
                'country' => 'United Kingdom',
                'nature_of_inquiry' => 'General Enquiry',
                'message' => 'How can we help you promote peace?',
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.form_name', 'contact');

        $form = Form::findOrFail($response->json('data.id'));
    } finally {
        DB::setDefaultConnection($testConnection);
        DB::disconnect('mysql_integration');
    }

    expect($form->name)->toBe('John Doe')
        ->and($form->email)->toBe('john@example.com')
        ->and($form->phone)->toBeNull()
        ->and($form->form_data['full_name'])->toBe('John Doe')
        ->and($form->form_data['country'])->toBe('United Kingdom')
        ->and($form->form_data['nature_of_inquiry'])->toBe('General Enquiry')
        ->and($form->form_data['message'])->toBe('How can we help you promote peace?');

    Event::assertDispatched(MessageSent::class, function (MessageSent $event) use ($expectedRecipient) {
        return collect($event->message->getTo())->contains(
            fn ($address) => $address->getAddress() === $expectedRecipient
        ) && collect($event->message->getReplyTo())->contains(
            fn ($address) => $address->getAddress() === 'john@example.com'
        ) && str_contains($event->message->getSubject(), 'Contact');
    });
})->group('mail-integration');

it('stores a donation and emails the configured admin', function () {
    Event::fake([MessageSent::class]);

    $testConnection = DB::getDefaultConnection();
    $integrationDatabase = trim((string) env('DB_INTEGRATION_DATABASE'));
    $integrationConnection = config('database.connections.mysql');
    $integrationConnection['database'] = $integrationDatabase;

    config(['database.connections.mysql_integration' => $integrationConnection]);
    DB::purge('mysql_integration');
    DB::setDefaultConnection('mysql_integration');

    try {
        if ($integrationDatabase === '' || ! Schema::hasTable('forms') || ! Schema::hasTable('payments')) {
            $this->fail('DB_INTEGRATION_DATABASE must contain the forms and payments tables.');
        }

        Currency::firstOrCreate(['code' => 'USD'], [
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1,
            'is_active' => true,
        ]);

        $sessionId = 'cs_test_'.Str::lower(Str::random(16));
        $session = Session::constructFrom([
            'id' => $sessionId,
            'url' => 'https://checkout.stripe.test/'.$sessionId,
        ]);

        $stripe = Mockery::mock(StripePayment::class);
        $stripe->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturnUsing(function (Payment $payment) use ($session) {
                $payment->forceFill(['stripe_checkout_session_id' => $session->id])->save();

                return $session;
            });
        app()->instance(StripePayment::class, $stripe);

        $response = $this->postJson('/api/donate', [
            'payment_type' => Payment::TYPE_ONE_TIME,
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1 555 123 4567',
            'currency' => 'USD',
            'amount' => 50,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.checkout_session_id', $sessionId)
            ->assertJsonPath('data.checkout_url', 'https://checkout.stripe.test/'.$sessionId);

        $this->assertDatabaseHas('payments', [
            'email' => 'john@example.com',
            'phone' => '+1 555 123 4567',
            'country' => 'Not provided',
            'amount' => 50,
            'payment_status' => Payment::STATUS_PENDING,
            'stripe_checkout_session_id' => $sessionId,
        ]);

        $donationForm = Form::findOrFail($response->json('data.form_submission_id'));

        expect($donationForm->form_name)->toBe('donation')
            ->and($donationForm->name)->toBe('John Doe')
            ->and($donationForm->email)->toBe('john@example.com')
            ->and($donationForm->form_data['payment_id'])->toBe($response->json('data.payment_id'))
            ->and($donationForm->form_data['payment_status'])->toBe(Payment::STATUS_PENDING);
    } finally {
        DB::setDefaultConnection($testConnection);
        DB::disconnect('mysql_integration');
    }

    Event::assertDispatched(MessageSent::class, function (MessageSent $event) {
        return collect($event->message->getTo())->contains(
            fn ($address) => $address->getAddress() === $this->mailRecipient
        ) && collect($event->message->getReplyTo())->contains(
            fn ($address) => $address->getAddress() === 'john@example.com'
        ) && str_contains($event->message->getSubject(), 'Donation');
    });
})->group('mail-integration');

it('keeps a failed donation submission when Stripe is unavailable', function () {
    Event::fake([MessageSent::class]);

    Currency::create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'exchange_rate' => 1,
        'is_active' => true,
    ]);

    $stripe = Mockery::mock(StripePayment::class);
    $stripe->shouldReceive('createCheckoutSession')
        ->once()
        ->andThrow(new RuntimeException('Stripe is unavailable.'));
    app()->instance(StripePayment::class, $stripe);

    $this->postJson('/api/donate', [
        'payment_type' => Payment::TYPE_ONE_TIME,
        'full_name' => 'Failed Donor',
        'email' => 'failed@example.com',
        'currency' => 'USD',
        'amount' => 25,
    ])->assertStatus(500);

    $this->assertDatabaseHas('payments', [
        'email' => 'failed@example.com',
        'amount' => 25,
        'payment_status' => Payment::STATUS_FAILED,
        'country' => 'Not provided',
    ]);

    $failedForm = Form::query()
        ->where('form_name', 'donation')
        ->where('email', 'failed@example.com')
        ->latest('id')
        ->firstOrFail();

    expect($failedForm->form_data['payment_status'])->toBe(Payment::STATUS_FAILED);

    Event::assertDispatched(MessageSent::class, fn (MessageSent $event) => collect($event->message->getTo())->contains(
        fn ($address) => $address->getAddress() === $this->mailRecipient
    ) && str_contains($event->message->getSubject(), 'Donation'));
})->group('mail-integration');
