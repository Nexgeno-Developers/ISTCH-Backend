<?php

// app/Mail/FormSubmissionMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

//class FormSubmissionMail extends Mailable implements ShouldQueue
class FormSubmissionMail extends Mailable
{
    //use Queueable, SerializesModels;
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
            ->subject('New '.ucfirst(str_replace('_', ' ', $this->formName)).' submission - '.config('app.name'))
            ->markdown('emails.form_submission');

        if (filter_var($this->data['email'] ?? null, FILTER_VALIDATE_EMAIL)) {
            $mail->replyTo($this->data['email'], $this->data['name'] ?? $this->data['full_name'] ?? null);
        }

        return $mail;
    }
}
