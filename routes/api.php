<?php

use Illuminate\Http\Request;
use App\Http\Middleware\Cors;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountyController;

// Ruta default Laravel (poți s-o ștergi dacă nu vrei Sanctum deloc)
// Route::get('/user', function (Request $request): mixed {
//     return $request->user();
// })->middleware('auth:sanctum');


// 🎯 API-ul tău public, fără autentificare
Route::middleware(['api', Cors::class])
    ->prefix('v1')
    ->group(function (): void {

        Route::get('/counties', [CountyController::class, 'index']);
        Route::get('/counties/{county}/localities', [CountyController::class, 'localities']);
        // Route::get('/counties/{county}/localities-grouped', [CountyController::class, 'localitiesGrouped']);
    
    });


Route::fallback(function (): JsonResponse {
    return response()->json([
        'error' => 'Endpoint not found',
        'status' => 404,
    ], 404);
});
