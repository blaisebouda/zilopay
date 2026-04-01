<?php

use App\Http\Controllers\Api\Transactions\DepositController;
use App\Http\Controllers\Api\Transactions\TransactionHistoryController;
use App\Http\Controllers\Api\Transactions\TransferController;
use App\Http\Controllers\Api\Transactions\WithdrawalController;
use App\Http\Controllers\Api\VaultController;
use Illuminate\Support\Facades\Route;

Route::prefix('transactions')->middleware('auth:sanctum')->group(function () {
    // Deposit routes
    Route::post('/init-deposit', [DepositController::class, 'init']);
    Route::post('/{reference}/confirm-deposit', [DepositController::class, 'confirm']);

    // Withdrawal routes
    Route::post('/init-withdrawal', [WithdrawalController::class, 'init']);
    Route::post('/{reference}/confirm-withdrawal', [WithdrawalController::class, 'confirm']);

    // Transfer routes
    Route::post('/transfer', [TransferController::class, 'store']);

    // History routes
    Route::get('/history', [TransactionHistoryController::class, 'index']);
    Route::get('/dashboard', [TransactionHistoryController::class, 'dashboard']);
});

// Vault Routes
Route::prefix('vaults')->middleware('auth:sanctum')->name('vaults.')->group(function () {
    Route::get('/', [VaultController::class, 'index'])->name('index');
    Route::post('/', [VaultController::class, 'store'])->name('store');
    Route::get('{vault:uuid}', [VaultController::class, 'show'])->middleware('can:view,vault')->name('show');
    Route::post('{vault:uuid}/deposit', [VaultController::class, 'deposit'])->middleware('can:deposit,vault')->name('deposit');
    Route::post('{vault:uuid}/withdraw', [VaultController::class, 'withdraw'])->middleware('can:withdraw,vault')->name('withdraw');
    Route::post('{vault:uuid}/toggle', [VaultController::class, 'toggle'])->middleware('can:update,vault')->name('toggle-status');
});
