<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cars/available', [CarController::class, 'available']);
    if (app()->environment('local')) {
        Route::post('/trips', [TripController::class, 'store']);
    }
});



