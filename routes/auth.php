<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register', [App\Http\Controllers\Api\Auth\AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/login', [App\Http\Controllers\Api\Auth\AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/verify-otp', [App\Http\Controllers\Api\Auth\OtpController::class, 'verifyOtp'])->middleware('throttle:5,1');
    Route::post('/resend-otp', [App\Http\Controllers\Api\Auth\OtpController::class, 'resendOtp'])->middleware('throttle:3,1');
    Route::post('/forgot-password', [App\Http\Controllers\Api\Auth\PasswordController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('/reset-password', [App\Http\Controllers\Api\Auth\PasswordController::class, 'resetPassword'])->middleware('throttle:5,1');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [App\Http\Controllers\Api\Auth\AuthController::class, 'me']);
        Route::post('/logout', [App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);
        Route::post('/logout-all', [App\Http\Controllers\Api\Auth\AuthController::class, 'logoutAll']);
        Route::post('/refresh', [App\Http\Controllers\Api\Auth\AuthController::class, 'refresh']);
    });
});
