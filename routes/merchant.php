<?php

use App\Http\Controllers\Merchant\MerchantApiKeyController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\MerchantDashboardController;
use App\Http\Controllers\Merchant\PaymentLinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchant')->name('merchant.')->group(function () {
    // Register and show merchant
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [MerchantController::class, 'show']);
        Route::post('/', [MerchantController::class, 'store']);
        Route::get('/documents/{path}', [MerchantController::class, 'downloadDocument'])
            ->can('view', 'Merchant')
            ->where('path', '.*')
            ->name('documents.download');
    });

    // Routes authentifiées — approved merchant
    Route::middleware(['auth:sanctum', 'merchant.approved'])->group(function () {
        Route::get('/dashboard', [MerchantDashboardController::class, 'index']);

        Route::get('/payment-links', [PaymentLinkController::class, 'index']);
        Route::get('/payments/{payment:uuid}', [PaymentLinkController::class, 'show']);
        Route::delete('/payments/{payment:uuid}', [PaymentLinkController::class, 'destroy']);

        Route::post('/api-keys', [MerchantApiKeyController::class, 'store']);
        Route::delete('/api-keys/{api_key:uuid}', [MerchantApiKeyController::class, 'destroy']);
    });

    // Routes API Key — intégration externe
    Route::middleware('merchant.api_key')->group(function () {
        Route::post('/payments/initiate', [PaymentLinkController::class, 'store']);
    });
});

// Public — lien de paiement
Route::middleware('validate.signed.payment.link')->group(function () {
    Route::get('/pay/{ref}', [PaymentLinkController::class, 'show'])->name('merchant.pay');
    Route::post('/pay/{ref}', [PaymentLinkController::class, 'process'])->name('process');
});
