<?php

use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\VaultController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
require __DIR__ . '/auth.php';

// Transaction Routes
require __DIR__ . '/transaction.php';


Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
    // Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
    // Route::get('{paymentMethod}', [PaymentMethodController::class, 'show'])->name('show');
    // Route::put('{paymentMethod}', [PaymentMethodController::class, 'update'])->name('update');
    // Route::delete('{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('destroy');
});
