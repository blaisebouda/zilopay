<?php

use App\Http\Controllers\Api\PaymentMethodController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
    Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
    Route::get('{paymentMethod}', [PaymentMethodController::class, 'show'])->name('show');
    Route::put('{paymentMethod}', [PaymentMethodController::class, 'update'])->name('update');
    Route::delete('{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('destroy');
});
