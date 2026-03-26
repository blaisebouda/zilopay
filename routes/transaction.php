<?php



use Illuminate\Support\Facades\Route;

Route::prefix('transactions')->middleware('auth:sanctum')->group(function () {
    // Deposit routes
    Route::post('/init-deposit', [App\Http\Controllers\Api\Transactions\DepositController::class, 'init']);
    Route::post('/confirm-deposit/{reference}', [App\Http\Controllers\Api\Transactions\DepositController::class, 'confirm']);

    // Withdrawal routes
    Route::post('/init-withdrawal', [App\Http\Controllers\Api\Transactions\WithdrawalController::class, 'init']);
    Route::post('/confirm-withdrawal/{reference}', [App\Http\Controllers\Api\Transactions\WithdrawalController::class, 'confirm']);

    // Transfer routes
    Route::post('/transfer', [App\Http\Controllers\Api\Transactions\TransferController::class, 'init']);
});
