<?php

namespace App\Providers;

use App\Events\SiteCreated;
use App\Events\UserLoggedIn;
use App\Events\UserRegistered;
use App\Listeners\NotifyAdminNewSite;
use App\Listeners\NotifyAdminNewUser;
use App\Listeners\UpdateLastLoginTime;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserLoggedIn::class => [
            UpdateLastLoginTime::class,
        ],
        UserRegistered::class => [
            NotifyAdminNewUser::class,
        ],
        SiteCreated::class => [
            NotifyAdminNewSite::class,
        ],
    ];
}
