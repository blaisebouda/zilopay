<?php


use Illuminate\Support\Facades\Route;

Route::prefix('merchant')->group(function () {
    // Register and show merchant 
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/{merchant:uuid}', [MerchantController::class, 'show']);
        Route::post('/', [MerchantController::class, 'store']);
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
