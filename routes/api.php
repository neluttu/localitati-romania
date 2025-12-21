<?php

use App\Http\Middleware\Cors;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CountyController;
use App\Http\Controllers\Api\V1\LookupController;
use App\Http\Controllers\Api\V1\LocalityController;
use App\Http\Controllers\Api\V1\CountyLocalitiesController;
use App\Http\Controllers\Api\V1\CountyLocalitiesLiteController;
use App\Http\Controllers\Api\V1\CountyLocalitiesGroupedController;


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
        Route::get('/counties', [CountyController::class, 'index'])->name('api.counties');

        // Localități dintr-un județ (structurat)
        // Route::get('/counties/{county}/localities', [CountyController::class, 'localities']);
        Route::get('/counties/{county}/localities', [CountyLocalitiesController::class, 'index'])->name('api.localities');

        Route::get('/counties/{county}/localities/lite', [CountyLocalitiesLiteController::class, 'index'])->name('api.localities.lite');
        Route::get('/counties/{county}/localities/grouped', [CountyLocalitiesGroupedController::class, 'index'])->name('api.localities.grouped');

        // Route::get('/counties/{county}/localities/{locality}',[CountyLocalityController::class, 'show'])->name('api.localities.show');
    
        // Route::get('/counties/{county}/localities/type/{type}',[CountyLocalitiesByTypeController::class, 'index'])->name('api.localities.by-type');
    
        // Detalii județ (AB, MS, CJ)
        Route::get('/counties/{county}', [CountyController::class, 'show']);

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
