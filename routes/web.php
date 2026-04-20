<?php

use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\OtpTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/otp', [OtpTestController::class, 'index']);

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/merchant-files/{path}', [MerchantController::class, 'downloadDocument'])
        ->where('path', '.*')
        ->can('view', 'Merchant')
        ->name('filament.merchant.download');
});
