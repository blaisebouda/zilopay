<?php

namespace App\Services\Transactions;

use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Services\Gateways\OrangeMoneyGateway;
use App\Services\Transactions\Contracts\TransactionServiceInterface;
use App\Services\Transactions\Utils\AmountValidator;
use App\Services\Transactions\Utils\FeeCalculator;
use App\Services\Wallet\Utils\WalletValidator;

class WithdrawalService extends AbstractTransactionService implements TransactionServiceInterface
{
    public function __construct(
        private WalletValidator $walletValidator,
        private AmountValidator $amountValidator,
        private MerchantFeeCalculator $feeCalculator,
        private OrangeMoneyGateway $gateway
    ) {}

    protected function getTransactionType(): TransactionType
    {
        return TransactionType::WITHDRAWAL;
    }

    protected function getRelation(): string
    {
        return 'withdrawal';
    }

    public function create(User $user, array $data): Transaction
    {
        return $this->executeInTransaction(function () use ($user, $data) {
            $wallet = $this->walletValidator->validateOwnership($user, $data['wallet_id']);
            $paymentMethod = $this->walletValidator->validatePaymentMethod($data['payment_method_id']);

            $amount = $data['amount'];
            $this->amountValidator->validateAgainstPaymentMethod($amount, $paymentMethod);

            $fees = $this->feeCalculator->calculate($amount, $paymentMethod);
            $totalDebit = $this->getTotalDebit($amount, $fees->total);

            $this->walletValidator->validateSufficientBalance($wallet, $totalDebit);

            $balanceBefore = $wallet->balance;
            $wallet->debit($totalDebit);

            $transaction = $this->createTransaction(
                user: $user,
                wallet: $wallet,
                amount: -$amount,
                status: TransactionStatus::PENDING,
                additionalData: [
                    'payment_method_id' => $paymentMethod->id,
                    'fee_fixed' => $fees->fixed,
                    'fee_percentage' => $fees->percentage,
                    'total' => -$totalDebit,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $wallet->balance,
                ]
            );

            $withdrawal = $this->createWithdrawalRecord(
                $transaction,
                $wallet,
                $data,
                $balanceBefore
            );

            $this->initiateGatewayPayout($withdrawal, $amount, $wallet, $totalDebit);

            return $transaction->fresh();
        });
    }

    public function confirm(string $reference, array $gatewayData): Transaction
    {
        return $this->executeInTransaction(function () use ($reference, $gatewayData) {
            $withdrawal = Withdrawal::whereHas('transaction', function ($q) {
                $q->where('status', TransactionStatus::PENDING);
            })->lockForUpdate()->first();

            if (! $withdrawal) {
                throw new \Exception('Withdrawal not found or already processed');
            }

            $verification = $this->gateway->verifyWithdrawal($reference);

            if (! $verification['success']) {
                return $this->failAndRefund($withdrawal, $verification, $gatewayData);
            }

            return $this->completeWithdrawal($withdrawal);
        });
    }

    public function cancel(string $reference, string $reason): Transaction
    {
        return $this->executeInTransaction(function () use ($reason) {
            $withdrawal = Withdrawal::whereHas('transaction', function ($q) {
                $q->where('status', TransactionStatus::PENDING);
            })->lockForUpdate()->first();

            if (! $withdrawal) {
                throw new \Exception('Withdrawal not found or not in pending status');
            }

            return $this->rejectAndRefund($withdrawal, $reason);
        });
    }

    private function createWithdrawalRecord(
        Transaction $transaction,
        Wallet $wallet,
        array $data,
        float $balanceBefore
    ): Withdrawal {
        return Withdrawal::create([
            'transaction_id' => $transaction->id,
            'wallet_id' => $wallet->id,
            'payout_details' => [
                'phone_number' => $data['phone_number'] ?? null,
                'account_name' => $data['account_name'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'account_number' => $data['account_number'] ?? null,
            ],
            'metadata' => $this->buildMetadata([
                'uuid' => $transaction->uuid,
                'initiated_by' => $transaction->user_id,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
            ]),
        ]);
    }

    private function initiateGatewayPayout(Withdrawal $withdrawal, float $amount, Wallet $wallet, float $totalDebit): void
    {
        $gatewayResponse = $this->gateway->initiateWithdrawal([
            'amount' => $amount,
            'currency' => $wallet->currency->code,
            'uuid' => $withdrawal->metadata['uuid'],
            'phone_number' => $withdrawal->payout_details['phone_number'] ?? null,
            'description' => 'Withdrawal from BaraPay wallet',
        ]);

        if (! $gatewayResponse['success']) {
            $wallet->credit($totalDebit);
            $this->updateTransactionStatus($withdrawal->transaction, TransactionStatus::BLOCKED);
            throw new \Exception('Gateway error: ' . ($gatewayResponse['message'] ?? 'Unknown error'));
        }

        $withdrawal->update([
            'metadata' => array_merge($withdrawal->metadata, [
                'external_reference' => $gatewayResponse['external_reference'] ?? null,
                'gateway_response' => $gatewayResponse,
                'gateway_initiated_at' => now()->toIso8601String(),
            ]),
        ]);

        $this->logOperation('initiated', [
            'withdrawal_id' => $withdrawal->id,
            'amount' => $amount,
            'uuid' => $withdrawal->metadata['uuid'],
        ]);
    }

    private function completeWithdrawal(Withdrawal $withdrawal): Transaction
    {
        $this->updateTransactionStatus(
            $withdrawal->transaction,
            TransactionStatus::SUCCESS,
            [
                'metadata' => array_merge($withdrawal->metadata, [
                    'completed_at' => now()->toIso8601String(),
                ]),
            ]
        );

        $this->logOperation('completed', [
            'withdrawal_id' => $withdrawal->id,
            'amount' => abs($withdrawal->transaction->amount),
            'uuid' => $withdrawal->metadata['uuid'],
        ]);

        return $withdrawal->transaction->fresh();
    }

    private function failAndRefund(Withdrawal $withdrawal, array $verification, array $gatewayData): Transaction
    {
        $wallet = Wallet::lockForUpdate()->find($withdrawal->wallet_id);
        $refundAmount = abs($withdrawal->transaction->total);
        $wallet->credit($refundAmount);

        $withdrawal->update([
            'metadata' => array_merge($withdrawal->metadata, [
                'failure_reason' => $verification['message'] ?? 'Verification failed',
                'gateway_data' => $gatewayData,
                'refund_amount' => $refundAmount,
            ]),
        ]);

        $this->updateTransactionStatus($withdrawal->transaction, TransactionStatus::BLOCKED);

        $this->logOperation('failed_and_refunded', [
            'withdrawal_id' => $withdrawal->id,
            'refund_amount' => $refundAmount,
            'uuid' => $withdrawal->metadata['uuid'],
        ]);

        throw new \Exception('Withdrawal verification failed: ' . ($verification['message'] ?? 'Unknown error'));
    }

    private function rejectAndRefund(Withdrawal $withdrawal, string $reason): Transaction
    {
        $wallet = Wallet::lockForUpdate()->find($withdrawal->wallet_id);
        $refundAmount = abs($withdrawal->transaction->total);
        $wallet->credit($refundAmount);

        $withdrawal->update([
            'metadata' => array_merge($withdrawal->metadata, [
                'rejected_at' => now()->toIso8601String(),
                'rejection_reason' => $reason,
                'refund_amount' => $refundAmount,
            ]),
        ]);

        $this->updateTransactionStatus($withdrawal->transaction, TransactionStatus::BLOCKED);

        $this->logOperation('rejected', [
            'withdrawal_id' => $withdrawal->id,
            'reason' => $reason,
            'refund_amount' => $refundAmount,
            'uuid' => $withdrawal->metadata['uuid'],
        ]);

        return $withdrawal->transaction->fresh();
    }
}
