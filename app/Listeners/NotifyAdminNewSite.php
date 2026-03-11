<?php

namespace App\Listeners;

use App\Events\SiteCreated;
use App\Mail\AdminNewSiteNotification;
use Illuminate\Support\Facades\Mail;

class NotifyAdminNewSite
{
    public function handle(SiteCreated $event): void
    {
        $adminEmail = config('app.admin_email');

        if (! $adminEmail) {
            return;
        }

        Mail::to($adminEmail)
            ->later(now()->addSeconds(10), new AdminNewSiteNotification($event->site));
    }
}
