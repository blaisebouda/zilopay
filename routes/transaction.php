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
    Route::post('/transfer', [App\Http\Controllers\Api\Transactions\TransferController::class, 'store']);

    // History routes
    Route::get('/history', [App\Http\Controllers\Api\Transactions\TransactionHistoryController::class, 'index']);
});


// Vault Routes
Route::prefix('vaults')->middleware('auth:sanctum')->name('vaults.')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\VaultController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Api\VaultController::class, 'store'])->name('store');
    Route::get('{vault:uuid}', [App\Http\Controllers\Api\VaultController::class, 'show'])->middleware('can:view,vault')->name('show');
    Route::post('{vault:uuid}/deposit', [App\Http\Controllers\Api\VaultController::class, 'deposit'])->middleware('can:deposit,vault')->name('deposit');
    Route::post('{vault:uuid}/withdraw', [App\Http\Controllers\Api\VaultController::class, 'withdraw'])->middleware('can:withdraw,vault')->name('withdraw');
    Route::post('{vault:uuid}/toggle', [App\Http\Controllers\Api\VaultController::class, 'toggle'])->middleware('can:update,vault')->name('toggle-status');
});
