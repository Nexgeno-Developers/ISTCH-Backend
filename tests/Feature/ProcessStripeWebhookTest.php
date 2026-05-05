<?php

use App\Jobs\ProcessStripeWebhook;
use App\Models\Payment;

it('reads invoice metadata and keeps the stripe invoice id on renewal payments', function () {
    $job = new ProcessStripeWebhook([
        'id' => 'evt_invoice_created',
        'type' => 'invoice.created',
        'created' => 1783231440,
    ]);

    $invoice = [
        'id' => 'in_renewal_123',
        'billing_reason' => 'subscription_cycle',
        'customer' => 'cus_123',
        'currency' => 'inr',
        'amount_paid' => 0,
        'amount_due' => 50000,
        'payment_intent' => 'pi_renewal_123',
        'metadata' => [
            'payment_id' => '7',
            'payment_group_id' => 'd15deb58-a978-40cf-9e3c-3a5b0e03689f',
        ],
    ];

    $sourcePayment = new Payment([
        'full_name' => 'umair Umair',
        'email' => 'umair.makent@gmail.com',
        'country' => 'India',
        'payment_group_id' => 'd15deb58-a978-40cf-9e3c-3a5b0e03689f',
        'payment_type' => Payment::TYPE_MONTHLY,
        'currency' => 'INR',
        'amount' => 500,
        'usd_amount' => 5.27,
        'exchange_rate' => 94.910504,
        'stripe_customer_id' => 'cus_original',
        'stripe_subscription_id' => 'sub_original',
        'payment_status' => Payment::STATUS_PAID,
        'meta' => ['stripe_mode' => 'sandbox'],
    ]);
    $sourcePayment->id = 7;

    $metadataMethod = new ReflectionMethod($job, 'metadataValueFromInvoice');
    $metadataMethod->setAccessible(true);

    expect($metadataMethod->invoke($job, $invoice, 'payment_id'))->toBe('7')
        ->and($metadataMethod->invoke($job, $invoice, 'payment_group_id'))->toBe('d15deb58-a978-40cf-9e3c-3a5b0e03689f');

    $attributesMethod = new ReflectionMethod($job, 'renewalPaymentAttributes');
    $attributesMethod->setAccessible(true);

    $attributes = $attributesMethod->invoke($job, $sourcePayment, $invoice);

    expect($attributes['stripe_invoice_id'])->toBe('in_renewal_123')
        ->and($attributes['payment_group_id'])->toBe('d15deb58-a978-40cf-9e3c-3a5b0e03689f')
        ->and($attributes['amount'])->toBe(500.0)
        ->and($attributes['payment_status'])->toBe(Payment::STATUS_PENDING);
});
