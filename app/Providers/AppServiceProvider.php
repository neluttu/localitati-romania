<?php

namespace App\Providers;

use App\Enums\UserRole;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Model::shouldBeStrict();

        Blade::if('admin', fn() => auth()->check() && auth()->user()->role === UserRole::Admin);
        Blade::if('user', fn() => auth()->check() && auth()->user()->role === UserRole::User);

    }
}
