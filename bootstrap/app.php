<?php

use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'v1',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            HandleCors::class,
            ForceJsonResponse::class,
            'throttle:120,1',
        ])->alias([
            'role' => RoleMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {})->create();
