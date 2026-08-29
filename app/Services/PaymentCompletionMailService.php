<?php

namespace App\Services;

use App\Helpers\AdminMailHelper;
use App\Mail\FormSubmissionMail;
use App\Models\Payment;

class PaymentCompletionMailService
{
    public function sendPaidNotificationIfNeeded(Payment $payment, string $source): bool
    {
        $payment->refresh();

        if ($payment->payment_status !== Payment::STATUS_PAID) {
            return false;
        }

        $sentAt = data_get($payment->meta, 'notifications.payment_paid_email.sent_at');
        if (is_string($sentAt) && trim($sentAt) !== '') {
            return false;
        }

        $sent = AdminMailHelper::send(
            new FormSubmissionMail('donation', [
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
                'paid_at' => optional($payment->paid_at)->toIso8601String(),
                'stripe_checkout_session_id' => $payment->stripe_checkout_session_id,
            ]),
            null,
            'notify_admin'
        );

        if (! $sent) {
            return false;
        }

        $payment->mergeMeta([
            'notifications' => [
                'payment_paid_email' => [
                    'sent_at' => now()->toIso8601String(),
                    'source' => $source,
                ],
            ],
        ]);

        return true;
    }
}
