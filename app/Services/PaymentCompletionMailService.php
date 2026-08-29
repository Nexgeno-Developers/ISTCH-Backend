<?php

namespace App\Services;

use App\Helpers\AdminMailHelper;
use App\Mail\FormSubmissionMail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentCompletionMailService
{
    public function sendPendingNotification(Payment $payment): bool
    {
        $payment->refresh();

        if ($payment->payment_status !== Payment::STATUS_PENDING) {
            return false;
        }

        $sentAt = data_get($payment->meta, 'notifications.payment_started_email.sent_at');
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
            ]),
            null,
            'notify_admin'
        );

        if (! $sent) {
            return false;
        }

        $payment->mergeMeta([
            'notifications' => [
                'payment_started_email' => [
                    'sent_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        return true;
    }

    public function sendPaidNotificationIfNeeded(Payment $payment, string $source): bool
    {
        $reservedPayment = DB::transaction(function () use ($payment, $source) {
            /** @var Payment|null $lockedPayment */
            $lockedPayment = Payment::query()->lockForUpdate()->find($payment->id);

            if (! $lockedPayment || $lockedPayment->payment_status !== Payment::STATUS_PAID) {
                return null;
            }

            $sentAt = data_get($lockedPayment->meta, 'notifications.payment_paid_email.sent_at');
            if (is_string($sentAt) && trim($sentAt) !== '') {
                return null;
            }

            $reservedAt = data_get($lockedPayment->meta, 'notifications.payment_paid_email.reserved_at');
            if (is_string($reservedAt) && trim($reservedAt) !== '') {
                return null;
            }

            $lockedPayment->mergeMeta([
                'notifications' => [
                    'payment_paid_email' => [
                        'reserved_at' => now()->toIso8601String(),
                        'source' => $source,
                    ],
                ],
            ]);

            return $lockedPayment->fresh();
        });

        if (! $reservedPayment) {
            return false;
        }

        $sent = AdminMailHelper::send(
            new FormSubmissionMail('donation', [
                'full_name' => $reservedPayment->full_name,
                'email' => $reservedPayment->email,
                'phone' => $reservedPayment->phone,
                'country' => $reservedPayment->country,
                'payment_type' => $reservedPayment->payment_type,
                'currency' => $reservedPayment->currency,
                'amount' => $reservedPayment->amount,
                'usd_amount' => $reservedPayment->usd_amount,
                'payment_status' => $reservedPayment->payment_status,
                'payment_group_id' => $reservedPayment->payment_group_id,
                'paid_at' => optional($reservedPayment->paid_at)->toIso8601String(),
            ]),
            null,
            'notify_admin'
        );

        $metaUpdate = $sent
            ? [
                'notifications' => [
                    'payment_paid_email' => [
                        'sent_at' => now()->toIso8601String(),
                        'source' => $source,
                        'reserved_at' => null,
                    ],
                ],
            ]
            : [
                'notifications' => [
                    'payment_paid_email' => [
                        'reserved_at' => null,
                    ],
                ],
            ];

        Payment::query()->whereKey($reservedPayment->id)->first()?->mergeMeta($metaUpdate);

        return $sent;
    }
}
