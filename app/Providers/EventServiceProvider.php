<?php

namespace App\Providers;

use App\Events\UserLoggedIn;
use App\Listeners\UpdateLastLoginTime;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserLoggedIn::class => [
            UpdateLastLoginTime::class,
        ],
    ];
}
