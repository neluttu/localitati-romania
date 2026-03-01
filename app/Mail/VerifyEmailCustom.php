<?php
declare(strict_types=1);
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class VerifyEmailCustom extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $verificationUrl,
        public $user
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmă adresa ta de email'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify',
            with: [
                'url' => $this->verificationUrl,
                'user' => $this->user,
            ]
        );
    }
}
