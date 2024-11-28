<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResource('/status', App\Http\Controllers\StatusController::class);

// Route::post('/status', 'App\Http\Controllers\StatusController@store');
// Route::get('/status', 'App\Http\Controllers\StatusController@index');
