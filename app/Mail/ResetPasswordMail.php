<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $token,
        public string $email
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Setează parola ta nouă!!!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'token' => $this->token,
                'email' => $this->email,
                'url' => url(route('password.reset', [
                    'token' => $this->token,
                    'email' => $this->email,
                ], false)),
            ]
        );
    }
}

