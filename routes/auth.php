<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->middleware('throttle:5,1');
    Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->middleware('throttle:3,1');
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->middleware('throttle:5,1');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});
