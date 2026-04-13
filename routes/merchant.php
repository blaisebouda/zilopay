<?php

use App\Http\Controllers\Merchant\MerchantApiKeyController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\MerchantDashboardController;
use App\Http\Controllers\Merchant\MerchantPaymentController;
use App\Http\Controllers\Merchant\PaymentLinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('merchant')->group(function () {
    // Register and show merchant
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [MerchantController::class, 'show']);
        Route::post('/', [MerchantController::class, 'store']);
        Route::get('/documents/{path}', [MerchantController::class, 'downloadDocument'])->name('merchant.documents.download');
    });

    // Routes authentifiées — approved merchant
    Route::middleware(['auth:sanctum', 'merchant.approved'])->group(function () {
        Route::get('/dashboard', [MerchantDashboardController::class, 'index']);
        Route::apiResource('/payment-links', PaymentLinkController::class);
        Route::post('/api-keys', [MerchantApiKeyController::class, 'store']);
        Route::delete('/api-keys/{api_key:uuid}', [MerchantApiKeyController::class, 'destroy']);
    });

    // Routes API Key — intégration externe
    Route::middleware('merchant.api_key')->group(function () {
        Route::post('/payments/initiate', [MerchantPaymentController::class, 'initiate']);
        Route::get('/payments/{payment:uuid}', [MerchantPaymentController::class, 'show']);
    });

    // Public — lien de paiement
    Route::get('/pay/{uuid}', [PaymentLinkController::class, 'show']);
    Route::post('/pay/{uuid}', [PaymentLinkController::class, 'process']);
});
