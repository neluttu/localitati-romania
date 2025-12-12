<?php

use Illuminate\Support\Facades\Route;

Route::get('/view/counties', fn() => view('api.counties'));
Route::get('/view/counties/{county}/localities', fn($county) => view('api.localities', ['county' => $county]));
// Route::get('/view/counties/{county}/localities-grouped', fn($county) => view('api.localities-grouped', ['county' => $county]));


Route::middleware('api')
    ->group(base_path('routes/api.php'));