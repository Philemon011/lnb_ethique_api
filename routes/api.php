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
Route::get('/messignalements/{user_id}', 'App\Http\Controllers\SignalementController@getUserSignalements');




Route::controller(App\Http\Controllers\RegisterController::class)->group(function()
{
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('me', 'getAuthenticatedUser')->middleware('auth:sanctum');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
    // Route::post('modifier_mot_de_passe', 'update');
    // Route::post('modifier_mot_de_passe_par_mail', 'sendResetLinkEmail');
    // Route::post('reset-password', 'resetPassword');
});
