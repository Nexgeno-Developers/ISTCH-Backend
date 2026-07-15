<?php

namespace App\Helpers;

use App\Models\Company;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminMailHelper
{
    /**
     * Send an email to the company's enquiry address, falling back to the
     * configured admin/from address. Mail failures are logged so a completed
     * form or payment submission is never lost because the mail server is down.
     */
    public static function send(Mailable $mailable, int|string|null $companyId = null): bool
    {
        $recipients = [];

        try {
            $recipients = self::recipients($companyId);

            if ($recipients === []) {
                Log::error('Admin email was not sent because no valid recipient is configured.');

                return false;
            }

            Mail::to($recipients)->send($mailable);

            return true;
        } catch (\Throwable $exception) {
            Log::error('Admin email could not be sent.', [
                'recipients' => $recipients,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @return array<int, string>
     */
    public static function recipients(int|string|null $companyId = null): array
    {
        $companyId ??= config('custom.company_id');
        $companyEmail = null;

        if (filled($companyId)) {
            $companyEmail = Company::query()->whereKey($companyId)->value('email');
        }

        $companyRecipients = self::validRecipients($companyEmail);

        if ($companyRecipients !== []) {
            return $companyRecipients;
        }

        $adminRecipients = self::validRecipients(config('custom.admin_email'));

        if ($adminRecipients !== []) {
            return $adminRecipients;
        }

        return self::validRecipients(config('mail.from.address'));
    }

    /**
     * @return array<int, string>
     */
    private static function validRecipients(array|string|null $configuredRecipients): array
    {
        $placeholderDomains = ['example.com', 'example.org', 'example.net'];

        $recipients = is_array($configuredRecipients)
            ? $configuredRecipients
            : preg_split('/[,;]+/', (string) $configuredRecipients);

        return collect($recipients ?: [])
            ->map(fn ($email) => trim((string) $email))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->reject(fn ($email) => in_array(strtolower((string) str($email)->afterLast('@')), $placeholderDomains, true))
            ->unique()
            ->values()
            ->all();
    }
}
