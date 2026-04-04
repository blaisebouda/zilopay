<?php

namespace App\Services\Transactions;

use App\Models\Deposit;
use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Gateways\OrangeMoneyGateway;
use App\Services\Transactions\Contracts\TransactionServiceInterface;
use App\Services\Wallet\Utils\WalletValidator;
use App\Utils\AmountValidator;
use App\Utils\FeeCalculator;

class DepositService extends AbstractTransactionService implements TransactionServiceInterface
{
    public function __construct(
        private WalletValidator $walletValidator,
        private AmountValidator $amountValidator,
        private OrangeMoneyGateway $gateway
    ) {}

    protected function getTransactionType(): TransactionType
    {
        return TransactionType::DEPOSIT;
    }

    protected function getRelation(): string
    {
        return 'deposit';
    }

    public function create(User $user, array $data): Transaction
    {
        return $this->executeInTransaction(function () use ($user, $data) {
            $wallet = $this->walletValidator->validateOwnership($user, $data['wallet_id']);
            $paymentMethod = $this->walletValidator->validatePaymentMethod($data['payment_method_id']);

            $amount = $data['amount'];
            $this->amountValidator->validateAgainstPaymentMethod($amount, $paymentMethod);

            $fee = FeeCalculator::make(
                amount: $amount,
                fixedFeedAmount: $paymentMethod->fee_fixed,
                percentageFee: $paymentMethod->fee_percent
            );

            $transaction = $this->createTransaction(
                userId: $user->id,
                currency: $wallet->currency,
                fee: $fee,
                status: TransactionStatus::PENDING,
                paymentMethodId: $paymentMethod->id,
                balanceBefore: $wallet->balance,
                balanceAfter: $wallet->balance,
            );

            $deposit = $this->createDepositRecord($transaction, $wallet, $fee);

            // $this->initiateGatewayPayment($deposit, $amount, $wallet, $data['phone_number'] ?? null);

            return $transaction->fresh();
        });
    }

    public function confirm(string $uuid, array $gatewayData): Transaction
    {
        return $this->executeInTransaction(function () use ($uuid, $gatewayData) {

            $transaction = $this->findPendingTransaction($uuid);

            $deposit = $transaction->deposit;

            if (! $deposit) {
                throw new \Exception('Deposit not found');
            }

            $verification = $this->gateway->verifyDeposit($uuid);

            if (! $verification['success']) {
                $this->failDeposit($deposit, $verification, $gatewayData);
                throw new \Exception('Deposit verification failed: '.($verification['message'] ?? 'Unknown error'));
            }

            return $this->completeDeposit($deposit);
        });
    }

    public function cancel(string $uuid, string $reason): Transaction
    {
        return $this->executeInTransaction(function () use ($uuid, $reason) {
            $transaction = $this->findPendingTransaction($uuid);

            $deposit = $transaction->deposit;

            if (! $deposit) {
                throw new \Exception('Deposit not found or not in pending status');
            }

            $this->gateway->cancelDeposit($deposit->metadata['external_uuid'] ?? null);

            $this->updateTransactionStatus(
                $transaction,
                TransactionStatus::BLOCKED
            );

            $this->logOperation('cancelled', [
                'deposit_id' => $deposit->id,
                'reason' => $reason,
            ]);

            return $deposit->transaction->fresh();
        });
    }

    private function createDepositRecord(Transaction $transaction, Wallet $wallet, FeeCalculator $fee): Deposit
    {
        return Deposit::create([
            'transaction_id' => $transaction->id,
            'wallet_id' => $wallet->id,
            'metadata' => $this->buildMetadata([
                ...$fee->breakdown(),
                'uuid' => $transaction->uuid,
                'initiated_by' => $transaction->user_id,
            ]),
        ]);
    }

    private function initiateGatewayPayment(Deposit $deposit, float $amount, Wallet $wallet, ?string $phoneNumber): void
    {
        $gatewayResponse = $this->gateway->initiateDeposit([
            'amount' => $amount,
            'currency' => $wallet->currency->code,
            'uuid' => $deposit->metadata['uuid'],
            'phone_number' => $phoneNumber,
            'description' => 'Deposit to BaraPay wallet',
        ]);

        $deposit->update([
            'metadata' => array_merge($deposit->metadata, [
                'external_uuid' => $gatewayResponse['external_uuid'] ?? null,
                'gateway_response' => $gatewayResponse,
                'gateway_initiated_at' => now()->toIso8601String(),
            ]),
        ]);

        $this->logOperation('initiated', [
            'deposit_id' => $deposit->id,
            'amount' => $amount,
            'uuid' => $deposit->metadata['uuid'],
        ]);
    }

    private function failDeposit(Deposit $deposit, array $verification, array $gatewayData): void
    {
        $deposit->update([
            'metadata' => array_merge($deposit->metadata, [
                'failure_reason' => $verification['message'] ?? 'Verification failed',
                'gateway_data' => $gatewayData,
            ]),
        ]);

        $this->updateTransactionStatus($deposit->transaction, TransactionStatus::BLOCKED);
    }

    private function completeDeposit(Deposit $deposit): Transaction
    {
        $wallet = Wallet::lockForUpdate()->find($deposit->wallet_id);
        $balanceBefore = $wallet->balance;

        $wallet->credit($deposit->transaction->amount);

        $deposit->update([
            'metadata' => array_merge($deposit->metadata, [
                'completed_at' => now()->toIso8601String(),
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
            ]),
        ]);

        $this->updateTransactionStatus(
            $deposit->transaction,
            TransactionStatus::SUCCESS,
            [
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
            ]
        );

        $this->logOperation('completed', [
            'deposit_id' => $deposit->id,
            'amount' => $deposit->transaction->amount,
            'wallet_id' => $wallet->id,
        ]);

        return $deposit->transaction->fresh();
    }
}
