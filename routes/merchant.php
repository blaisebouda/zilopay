<?php

use App\Http\Controllers\Merchant\MerchantApiKeyController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\MerchantDashboardController;
use App\Http\Controllers\Merchant\PaymentLinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchant')->name('merchant.')->group(function () {
    // Register and show merchant
    Route::middleware(['auth:sanctum', 'throttle:5,1'])->group(function () {
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
        Route::get('/payment-links/{payment:uuid}', [PaymentLinkController::class, 'show']);
        Route::delete('/payment-links/{payment:uuid}', [PaymentLinkController::class, 'destroy']);

        Route::get('/api-keys', [MerchantApiKeyController::class, 'index']);
        Route::post('/api-keys', [MerchantApiKeyController::class, 'store']);
        Route::post('/api-keys/{merchantApiKey:uuid}/toggle-active', [MerchantApiKeyController::class, 'toggleActive']);
        Route::delete('/api-keys/{merchantApiKey:uuid}', [MerchantApiKeyController::class, 'destroy']);
    });

    // Routes API Key — intégration externe
    Route::middleware(['merchant.api_key', 'throttle:5,1'])->group(function () {
        Route::post('/payment/initiate', [PaymentLinkController::class, 'store']);
    });
});
