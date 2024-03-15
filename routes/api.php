<?php

use App\Http\Controllers\CollectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(CollectionController::class)->group(function () {
    Route::get('/cards', 'getAllCards');
    Route::get('/cards/{id}', 'getSingleCard');
    Route::put('/cards/{id}', 'changeCard');
    Route::post('/cards', 'addCard');
    Route::delete('/cards/{id}', 'deleteCard');
});
