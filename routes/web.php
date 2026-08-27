<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Merchant\MerchantController;
use App\Http\Controllers\Api\Merchant\PaymentLinkController;
use App\Http\Controllers\Inertia\PayLink;
use App\Http\Controllers\OtpTestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Dev\TestDataController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'loginByWeb']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// Auth routes
Route::get('/login', fn() => Inertia::render('Auth/Login'))->name('login');
Route::get('/register', fn() => Inertia::render('Auth/Register'))->name('register');
Route::get('/reset-password', fn() => Inertia::render('Auth/ResetPassword'))->name('reset-password');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/deposit', fn() => Inertia::render('Deposit/Index'))->name('deposit');
    Route::get('/transfer', fn() => Inertia::render('Transfer/Index'))->name('transfer');
    Route::get('/merchants/create', fn() => Inertia::render('Dashboard/Merchant/MerchantCreate'))->name('merchants.create');
});

// Dashboard routes
Route::middleware(['auth:sanctum'])->prefix('dashboard')->group(function () {
    Route::get('/', fn() => Inertia::render('Dashboard/Index'))->name('dashboard');
    Route::get('/transactions', fn() => Inertia::render('Dashboard/Transaction'))->name('transactions');
    Route::get('/wallets', fn() => Inertia::render('Dashboard/Wallet/Index'))->name('wallets');
    Route::get('/withdraws', fn() => Inertia::render('Dashboard/Withdraw/Index'))->name('withdraws');
    Route::get('/merchants', fn() => Inertia::render('Dashboard/Merchant/Index'))->name('merchants');
    Route::get('/api-keys', fn() => Inertia::render('Dashboard/Merchant/Api-Key/Index'))->name('api-keys');
    Route::get('/settings', fn() => Inertia::render('Dashboard/Settings/Index'))->name('settings');
});



Route::get('/otp', [OtpTestController::class, 'index']);

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/merchant-files/{path}', [MerchantController::class, 'downloadDocument'])
        ->where('path', '.*')
        ->can('view', 'Merchant')
        ->name('filament.merchant.download');
});

// Development helper: idempotent seeding via web (only in local env)
Route::get('/dev/seed-test-data', [TestDataController::class, 'seed'])->name('dev.seed_test_data');

// Public — lien de paiement
Route::middleware(['signed', 'throttle:3,1'])->group(function () {
    Route::get('/pay/{ref}', [PayLink::class, 'index'])->name('merchant.pay');
    Route::post('/pay/{ref}', [PaymentLinkController::class, 'process'])->name('process');
});
