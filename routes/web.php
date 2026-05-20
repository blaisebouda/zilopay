<?php

use App\Http\Controllers\Api\Merchant\MerchantController;
use App\Http\Controllers\Api\Merchant\PaymentLinkController;
use App\Http\Controllers\Inertia\PayLink;
use App\Http\Controllers\OtpTestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
Route::get('/register', fn () => Inertia::render('Auth/Register'))->name('register');

// Dashboard routes
Route::prefix('dashboard')->group(function () {
    Route::get('/', fn () => Inertia::render('Dashboard/Index'))->name('dashboard');
});

Route::get('/otp', [OtpTestController::class, 'index']);

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/merchant-files/{path}', [MerchantController::class, 'downloadDocument'])
        ->where('path', '.*')
        ->can('view', 'Merchant')
        ->name('filament.merchant.download');
});

// Public — lien de paiement
Route::middleware(['signed', 'throttle:3,1'])->group(function () {
    Route::get('/pay/{ref}', [PayLink::class, 'index'])->name('merchant.pay');
    Route::post('/pay/{ref}', [PaymentLinkController::class, 'process'])->name('process');
});
