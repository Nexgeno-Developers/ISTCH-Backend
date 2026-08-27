<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Upload;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSubmissionMail extends Mailable
{
    use SerializesModels;

    public string $formName;

    public array $data;

    public function __construct(string $formName, array $data)
    {
        $this->formName = $formName;
        $this->data = $data;
    }

    public function build()
    {
        $mail = $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->subjectLine())
            ->view('emails.form_submission_html', [
                'brandName' => $this->brandName(),
                'brandWebsite' => $this->brandWebsite(),
                'emailTitle' => $this->emailTitle(),
                'rows' => $this->formattedRows(),
                'logoPath' => $this->logoPath(),
                'logoUrl' => $this->logoUrl(),
            ]);

        if (filter_var($this->data['email'] ?? null, FILTER_VALIDATE_EMAIL)) {
            $mail->replyTo($this->data['email'], $this->data['name'] ?? $this->data['full_name'] ?? null);
        }

        return $mail;
    }

    private function subjectLine(): string
    {
        return 'New '.$this->readableFormName().' submission - '.config('app.name');
    }

    private function emailTitle(): string
    {
        return $this->readableFormName().' Form Submission';
    }

    private function readableFormName(): string
    {
        return ucfirst(str_replace('_', ' ', $this->formName));
    }

    private function brandName(): string
    {
        return (string) get_setting('name', config('app.name'));
    }

    private function brandWebsite(): string
    {
        $website = (string) get_setting('website', '');

        if ($website !== '') {
            if (! str_starts_with($website, 'http://') && ! str_starts_with($website, 'https://')) {
                return 'https://'.$website;
            }

            return $website;
        }

        return (string) config('app.url');
    }

    private function logoPath(): ?string
    {
        $logo = $this->logoUpload();

        if (! $logo || filled($logo->external_link) || blank($logo->file_name)) {
            return null;
        }

        $path = public_path($logo->file_name);

        return is_file($path) ? $path : null;
    }

    private function logoUrl(): ?string
    {
        $logo = $this->logoUpload();

        if ($logo) {
            if (filled($logo->external_link)) {
                return (string) $logo->external_link;
            }

            if (filled($logo->file_name)) {
                return central_asset($logo->file_name);
            }
        }

        $fallbackPath = public_path('assets/backend/img/logo.png');

        if (is_file($fallbackPath)) {
            return central_asset('assets/backend/img/logo.png');
        }

        return null;
    }

    private function logoUpload(): ?Upload
    {
        $logoId = get_setting('logo');

        if (! filled($logoId)) {
            return null;
        }

        return Upload::find($logoId);
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    private function formattedRows(): array
    {
        $rows = [];

        foreach ($this->data as $key => $value) {
            $rows[] = [
                'label' => ucwords(str_replace('_', ' ', (string) $key)),
                'value' => $this->formatValue((string) $key, $value),
            ];
        }

        return $rows;
    }

    private function formatValue(string $key, mixed $value): string
    {
        if ($key === 'payment_type' && is_scalar($value) && $value !== null) {
            return $this->formatPaymentType((string) $value);
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            $items = array_map(function ($item) use ($key) {
                if (is_scalar($item) || $item === null) {
                    return $this->formatValue($key, $item);
                }

                return json_encode($item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: 'N/A';
            }, $value);

            $items = array_values(array_filter(array_map('trim', $items), fn ($item) => $item !== ''));

            return $items !== [] ? implode(', ', $items) : 'N/A';
        }

        if ($value === null) {
            return 'N/A';
        }

        $string = trim((string) $value);

        return $string !== '' ? $string : 'N/A';
    }

    private function formatPaymentType(string $value): string
    {
        $normalized = trim($value);

        if ($normalized === Payment::TYPE_ONE_TIME) {
            return 'One-Time';
        }

        return ucfirst(str_replace('_', ' ', strtolower($normalized)));
    }
}
