<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResource('/status', App\Http\Controllers\StatusController::class);
Route::apiResource('/typeSignalement', App\Http\Controllers\TypeSignalementController::class);
Route::apiResource('/signalement', App\Http\Controllers\SignalementController::class);
Route::apiResource('/raison', App\Http\Controllers\RaisonController::class);
Route::post('/getSignalementByCodeDeSuivi', 'App\Http\Controllers\SignalementController@getSignalementByCodeDeSuivi');
