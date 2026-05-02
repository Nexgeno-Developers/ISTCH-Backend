<?php

namespace App\Console\Commands;

use App\Payments\StripePayment;
use Illuminate\Console\Command;

class ReconcilePendingPayments extends Command
{
    protected $signature = 'payments:reconcile-pending
        {--limit= : Maximum number of pending payments to check}
        {--older-than-days= : Only check pending payments created this many days ago or earlier}';

    protected $description = 'Check pending Stripe Checkout payments and update local payment statuses.';

    public function handle(StripePayment $stripePayment): int
    {
        $limit = $this->option('limit') !== null
            ? max(1, (int) $this->option('limit'))
            : null;

        $olderThanDays = $this->option('older-than-days') !== null
            ? max(1, (int) $this->option('older-than-days'))
            : null;

        $summary = $stripePayment->reconcilePendingPayments($limit, $olderThanDays);

        $this->info('Pending payment reconciliation completed.');
        if ($olderThanDays !== null) {
            $this->line('Older than days: ' . $olderThanDays);
        }
        $this->line('Checked: ' . $summary['checked']);
        $this->line('Paid: ' . $summary['paid']);
        $this->line('Cancelled: ' . $summary['cancelled']);
        $this->line('Still pending: ' . $summary['pending']);

        if (! empty($summary['errors'])) {
            $this->warn('Errors: ' . count($summary['errors']));
            $this->table(
                ['Payment ID', 'Session ID', 'Message'],
                collect($summary['errors'])->map(fn (array $error) => [
                    $error['payment_id'],
                    $error['session_id'],
                    $error['message'],
                ])->all()
            );
        }

        return self::SUCCESS;
    }
}
