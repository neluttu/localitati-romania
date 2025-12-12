<?php

use App\Http\Middleware\Cors;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountyController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\LocalityController;


// API public, fără autentificare
Route::middleware(['api', Cors::class])
    ->prefix('v1')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Counties (Județe)
        |--------------------------------------------------------------------------
        */

        // Listă județe
        Route::get('/counties', [CountyController::class, 'index']);

        // Detalii județ (AB, MS, CJ)
        Route::get('/counties/{county}', [CountyController::class, 'show']);

        // Localități dintr-un județ (structurat)
        Route::get('/counties/{county}/localities', [CountyController::class, 'localities']);

        // Localități dintr-un județ – flat
        Route::get('/counties/{county}/localities/flat', [CountyController::class, 'localitiesFlat']);


        /*
        |--------------------------------------------------------------------------
        | Localities (Global, flat, filtrabil)
        |--------------------------------------------------------------------------
        */

        // Search / autocomplete / filtre
        Route::get('/localities', [LocalityController::class, 'index']);

        // Detaliu localitate (SIRUTA)
        Route::get('/localities/{siruta}', [LocalityController::class, 'show']);


        /*
        |--------------------------------------------------------------------------
        | Lookups / Metadata
        |--------------------------------------------------------------------------
        */

        Route::get('/lookups/locality-types', [LookupController::class, 'localityTypes']);
        Route::get('/lookups/regions', [LookupController::class, 'regions']);


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
        });
    });



Route::fallback(function (): JsonResponse {
    return response()->json([
        'error' => 'Endpoint not found',
        'status' => 404,
    ], 404);
});
