<?php

use App\Http\Controllers\OtpTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/otp', [OtpTestController::class, 'index']);
