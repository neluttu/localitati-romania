<?php
declare(strict_types=1);
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Mail\ResetPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordQueued extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public function __construct($token)
    {
        parent::__construct($token);

        $this->delay(now()->addSeconds(10));
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new ResetPasswordMail(
            $this->token,
            $notifiable->email
        ))->to($notifiable->email);
    }
}
