<?php

use App\Http\Controllers\Api\CountyController;

Route::get('/counties', [CountyController::class, 'index']);
Route::get('/counties/{county}/localities', [CountyController::class, 'localities']);
Route::get('/counties/{county}/localities-grouped', [CountyController::class, 'localitiesGrouped']);