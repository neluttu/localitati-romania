<?php

namespace App\Mail;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewSiteNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Site $site
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Admin] Site nou adăugat: '.$this->site->domain,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-site',
            with: [
                'site' => $this->site,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
