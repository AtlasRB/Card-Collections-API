<?php

use App\Http\Controllers\CollectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(CollectionController::class)->group(function () {
    Route::get('/card', 'getAllCards');
    Route::get('/card/{id}', 'getSingleCard');
    Route::put('/card/{id}', 'changeCard');
    Route::post('/card', 'addCard');
    Route::delete('/card/{id}', 'deleteCard');
});
