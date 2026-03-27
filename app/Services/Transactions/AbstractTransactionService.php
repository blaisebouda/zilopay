<?php

namespace App\Services\Transactions;

use App\Models\Enums\Currency;
use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use App\Models\Transaction;
use App\Services\Transactions\Utils\AmountWithFeeResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class AbstractTransactionService
{
    /**
     * Get the transaction type for this service
     */
    abstract protected function getTransactionType(): TransactionType;

    /**
     * Get relation to load with transaction
     */
    abstract protected function getRelation(): string;

    /**
     * Execute within a database transaction
     */
    protected function executeInTransaction(callable $callback, int $retries = 3)
    {
        return DB::transaction($callback, $retries);
    }

    /**
     * Create main transaction record
     */
    protected function createTransaction(
        int $userId,
        Currency $currency,
        AmountWithFeeResult $amountWithFee,
        float $balanceBefore,
        float $balanceAfter,
        TransactionStatus $status,
        ?int $paymentMethodId = null
    ): Transaction {
        $transaction = Transaction::create([
            'user_id' => $userId,
            'currency' => $currency->value,
            'type' => $this->getTransactionType(),
            'amount' => $amountWithFee->amount,
            'status' => $status,
            'fee_fixed' => $amountWithFee->fixed,
            'fee_percentage' => $amountWithFee->percentage,
            'total' => $amountWithFee->getTotalDebit(),
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'payment_method_id' => $paymentMethodId,
        ]);

        Log::info('Transaction created', [
            'transaction_id' => $transaction->id,
            'user_id' => $userId,
            'type' => $this->getTransactionType()->name,
            'amount' => $amountWithFee->amount,
            'status' => $status->name,
        ]);

        return $transaction;
    }

    /**
     * Update transaction status
     */
    protected function updateTransactionStatus(
        Transaction $transaction,
        TransactionStatus $status,
        array $additionalData = []
    ): void {
        $transaction->update([
            'status' => $status,
            ...$additionalData,
        ]);

        Log::info('Transaction status updated', [
            'transaction_id' => $transaction->id,
            'new_status' => $status->name,
        ]);
    }

    /**
     * Build metadata with common fields
     */
    protected function buildMetadata(array $data = []): array
    {
        return array_merge([
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ], $data);
    }

    /**
     * Find pending transaction by UUID with type, relation and lock for update
     */
    protected function findPendingTransaction(string $uuid): Transaction
    {
        return Transaction::whereUuidForType($uuid, $this->getTransactionType())
            ->where('status', TransactionStatus::PENDING)
            ->with($this->getRelation())
            ->lockForUpdate()
            ->firstOrFail();
    }

    /**
     * Log operation with context
     */
    protected function logOperation(string $action, array $context): void
    {
        Log::info("{$this->getTransactionType()->name} {$action}", $context);
    }
}
