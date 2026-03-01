<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;

class UpdateLastLoginTime
{
    public function handle(UserLoggedIn $event): void
    {
        $event->user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'last_login_user_agent' => request()->userAgent(),
            'login_count' => $event->user->login_count + 1,
        ]);
    }
}
