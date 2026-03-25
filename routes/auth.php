<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register'])->middleware('rate.limit:5,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('rate.limit:10,1');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('rate.limit:5,1');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->middleware('rate.limit:3,1');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('rate.limit:3,1');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('rate.limit:5,1');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/accept-charter', [AuthController::class, 'acceptCharter']);
    });
});
