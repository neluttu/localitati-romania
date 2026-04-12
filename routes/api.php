<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CountyController;
use App\Http\Controllers\Api\V1\CountyLocalitiesController;
use App\Http\Controllers\Api\V1\CountyLocalitiesGroupedController;
use App\Http\Controllers\Api\V1\CountyLocalitiesLiteController;
use App\Http\Controllers\Api\V1\LocalityController;
use App\Http\Controllers\Api\V1\LookupController;
use App\Http\Middleware\ApiAccessLog;
use App\Http\Middleware\Cors;
use App\Http\Middleware\ValidateSiteToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', Cors::class])
    ->prefix('v1')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Auth Routes (for user authentication - Sanctum tokens)
        |--------------------------------------------------------------------------
        */
        Route::prefix('auth')->group(function (): void {
            Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
            Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

            Route::middleware('auth:sanctum')->group(function (): void {
                Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
                Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Public Health Check (no auth required)
        |--------------------------------------------------------------------------
        */
        Route::get('/health', function () {
            return response()->json([
                'status' => 'ok',
                'timestamp' => now()->toIso8601String(),
            ]);
        })->name('api.health');

        /*
        |--------------------------------------------------------------------------
        | Protected API Routes - Require Site Token (X-Site-Token header)
        | Token must match the domain from Origin/Referer header
        |--------------------------------------------------------------------------
        */
        Route::middleware([ValidateSiteToken::class, ApiAccessLog::class])->group(function (): void {

            /*
            |--------------------------------------------------------------------------
            | Counties (Județe)
            |--------------------------------------------------------------------------
            */
            Route::get('/counties', [CountyController::class, 'index'])->name('api.counties');
            Route::get('/counties/{county}', [CountyController::class, 'show'])->name('api.counties.show');
            Route::get('/counties/{county}/localities', [CountyLocalitiesController::class, 'index'])->name('api.localities');
            Route::get('/counties/{county}/localities/lite', [CountyLocalitiesLiteController::class, 'index'])->name('api.localities.lite');
            Route::get('/counties/{county}/localities/grouped', [CountyLocalitiesGroupedController::class, 'index'])->name('api.localities.grouped');
            Route::get('/counties/{county}/localities/flat', [CountyController::class, 'localitiesFlat'])->name('api.localities.flat');

            /*
            |--------------------------------------------------------------------------
            | Localities (Global, flat, filtrabil)
            |--------------------------------------------------------------------------
            */
            Route::get('/localities', [LocalityController::class, 'index'])->name('api.localities.index');
            Route::get('/localities/{siruta}', [LocalityController::class, 'show'])->name('api.localities.show');

            /*
            |--------------------------------------------------------------------------
            | Lookups / Metadata
            |--------------------------------------------------------------------------
            */
            Route::get('/lookups/locality-types', [LookupController::class, 'localityTypes'])->name('api.lookups.locality-types');
            Route::get('/lookups/regions', [LookupController::class, 'regions'])->name('api.lookups.regions');

            Route::get('/health/cache', function () {
                $key = 'health_check';

                cache()->put($key, 'ok', 10);

                return response()->json([
                    'API CACHE STATUS:' => '',
                    'cache_store' => config('cache.default'),
                    'cache_driver' => get_class(cache()->getStore()),
                    'cache_write' => cache()->has($key),
                    'cache_value' => cache()->get($key),
                    'timestamp' => now()->toDateTimeString(),
                ]);
            })->name('api.health.cache');
        });
    });

Route::fallback(function (): JsonResponse {
    return response()->json([
        'error' => 'Endpoint not found',
        'status' => 404,
    ], 404);
});
