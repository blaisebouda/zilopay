<?php

use App\Http\Controllers\Api\Merchant\MerchantController;
use App\Http\Controllers\Api\Merchant\PaymentLinkController;
use App\Http\Controllers\OtpTestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return Inertia::render('Dashboard');
});


Route::get('/otp', [OtpTestController::class, 'index']);

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/merchant-files/{path}', [MerchantController::class, 'downloadDocument'])
        ->where('path', '.*')
        ->can('view', 'Merchant')
        ->name('filament.merchant.download');
});

Route::inertia('/', 'Dashboard');

// Public — lien de paiement
Route::middleware(['signed', 'throttle:3,1'])->group(function () {
    Route::get('/pay/{ref}', [PaymentLinkController::class, 'show'])->name('merchant.pay');
    Route::post('/pay/{ref}', [PaymentLinkController::class, 'process'])->name('process');
});
