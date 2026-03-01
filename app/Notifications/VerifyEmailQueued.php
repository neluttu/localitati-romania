<?php
declare(strict_types=1);
namespace App\Notifications;

use App\Mail\VerifyEmailCustom;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->delay(now()->addSeconds(10));
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): VerifyEmailCustom
    {
        // Obținem URL-ul de verificare exact cum o face Laravel default
        $url = $this->verificationUrl($notifiable);

        return (new VerifyEmailCustom(
            $url,
            $notifiable
        ))->to($notifiable->email);
    }
}
