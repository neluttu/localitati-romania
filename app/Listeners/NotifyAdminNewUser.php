<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\AdminNewUserNotification;
use Illuminate\Support\Facades\Mail;

class NotifyAdminNewUser
{
    public function handle(UserRegistered $event): void
    {
        $adminEmail = config('app.admin_email');

        if (! $adminEmail) {
            return;
        }

        Mail::to($adminEmail)
            ->later(now()->addSeconds(10), new AdminNewUserNotification($event->user));
    }
}
