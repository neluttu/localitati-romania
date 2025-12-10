<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountyController;

// Ruta default Laravel (poți s-o ștergi dacă nu vrei Sanctum deloc)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// 🎯 API-ul tău public, fără autentificare
Route::prefix('v1')->group(function () {

    Route::get('/counties', [CountyController::class, 'index']);
    Route::get('/counties/{county}/localities', [CountyController::class, 'localities']);
    Route::get('/counties/{county}/localities-grouped', [CountyController::class, 'localitiesGrouped']);

});

Route::fallback(function () {
    return response()->json([
        'error' => 'Endpoint not found',
        'status' => 404,
    ], 404);
});
