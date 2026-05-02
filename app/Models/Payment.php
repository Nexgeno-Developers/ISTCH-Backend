<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const TYPE_ONE_TIME = 'one_time';
    public const TYPE_MONTHLY = 'monthly';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'country',
        'payment_type',
        'currency',
        'amount',
        'usd_amount',
        'exchange_rate',
        'stripe_customer_id',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'stripe_subscription_id',
        'stripe_invoice_id',
        'payment_status',
        'webhook_received_at',
        'paid_at',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'usd_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'webhook_received_at' => 'datetime',
        'paid_at' => 'datetime',
        'meta' => 'array',
    ];

    public function markPaid(?string $paymentIntentId = null, ?string $invoiceId = null): void
    {
        $this->forceFill([
            'payment_status' => self::STATUS_PAID,
            'stripe_payment_intent_id' => $paymentIntentId ?: $this->stripe_payment_intent_id,
            'stripe_invoice_id' => $invoiceId ?: $this->stripe_invoice_id,
            'paid_at' => $this->paid_at ?: now(),
        ])->save();
    }

    public function mergeMeta(array $data): void
    {
        $this->forceFill([
            'meta' => array_replace_recursive($this->meta ?? [], $data),
        ])->save();
    }
}
