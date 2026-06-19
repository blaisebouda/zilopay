<?php

use App\Http\Controllers\Api\PaymentMethodController;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Middlewares\CacheResponse;

use function Illuminate\Support\hours;

// Authentication Routes
require __DIR__.'/auth.php';

// Transaction Routes
require __DIR__.'/transaction.php';

// Merchant Routes
require __DIR__.'/merchant.php';

require __DIR__.'/options.php';

Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
    //  ->middleware(CacheResponse::for(hours(24))); // Enable in production

});
