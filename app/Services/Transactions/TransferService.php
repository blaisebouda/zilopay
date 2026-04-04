<?php

namespace App\Services\Transactions;

use App\Models\Enums\Currency;
use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Transactions\Contracts\TransactionServiceInterface;
use App\Services\Wallet\Utils\WalletValidator;
use App\Utils\AmountValidator;
use App\Utils\FeeCalculator;
use Illuminate\Database\Eloquent\Collection;

class TransferService extends AbstractTransactionService implements TransactionServiceInterface
{
    public function __construct(
        private WalletValidator $walletValidator,
        private AmountValidator $amountValidator,

    ) {}

    protected function getTransactionType(): TransactionType
    {
        return TransactionType::TRANSFER;
    }

    protected function getRelation(): string
    {
        return 'transfer';
    }

    public function create(User $sender, array $data): Transaction
    {
        return $this->executeInTransaction(function () use ($sender, $data) {
            $senderWallet = $this->walletValidator->validateOwnership($sender, $data['sender_wallet_id']);
            $receiverWallet = $this->walletValidator->validateExists($data['receiver_wallet_id']);

            $this->walletValidator->validateDifferentWallets($senderWallet, $receiverWallet);
            $this->walletValidator->validateSameCurrency($senderWallet, $receiverWallet);

            $amount = $data['amount'];
            $this->amountValidator->validateTransferAmount($amount);

            $fee = $this->calculateTransferFee($amount);

            $this->walletValidator->validateSufficientBalance($senderWallet, $fee->getTotalDebit());

            return $this->executeTransfer($sender, $senderWallet, $receiverWallet, $fee, $data['note'] ?? null);
        }, 3);
    }

    public function confirm(string $uuid, array $gatewayData): Transaction
    {
        throw new \BadMethodCallException('Transfers are completed immediately and cannot be confirmed');
    }

    public function cancel(string $uuid, string $reason): Transaction
    {
        throw new \BadMethodCallException('Transfers cannot be cancelled');
    }

    public function getTransferHistory(User $user, array $filters = []): Collection
    {
        $query = Transaction::transfers()->forUser($user->id)->latest();

        $this->applyFilters($query, $filters);

        return $query->limit(30)->get();
    }

    private function executeTransfer(
        User $sender,
        Wallet $senderWallet,
        Wallet $receiverWallet,
        FeeCalculator $fee,
        ?string $note
    ): Transaction {
        $senderWalletLocked = Wallet::lockForUpdate()->find($senderWallet->id);
        $receiverWalletLocked = Wallet::lockForUpdate()->find($receiverWallet->id);

        $senderBalanceBefore = $senderWalletLocked->balance;
        $receiverBalanceBefore = $receiverWalletLocked->balance;

        $totalDebit = $fee->getTotalDebit();
        $senderWalletLocked->debit($totalDebit);
        $receiverWalletLocked->credit($fee->getAmount());

        $senderTransaction = $this->createSenderTransaction(
            sender: $sender,
            wallet: $senderWalletLocked,
            fee: $fee,
            balanceBefore: $senderBalanceBefore,
            balanceAfter: $senderWalletLocked->balance,
        );

        $this->createReceiverTransaction(
            userId: $receiverWalletLocked->user_id,
            currency: $receiverWalletLocked->currency,
            amount: $fee->getAmount(),
            balanceBefore: $receiverBalanceBefore,
            balanceAfter: $receiverWalletLocked->balance
        );

        $transfer = $this->createTransferRecord(
            senderTransaction: $senderTransaction,
            senderWallet: $senderWalletLocked,
            receiverWallet: $receiverWalletLocked,
            fee: $fee,
            note: $note
        );

        $this->logTransfer(
            $transfer,
            $sender,
            $receiverWalletLocked,
            $fee->getAmount(),
            $fee->getPlatformFeeAmount(),
            $senderBalanceBefore,
            $receiverBalanceBefore,
            $senderWalletLocked,
            $receiverWalletLocked
        );

        return $senderTransaction->fresh();
    }

    private function createSenderTransaction(
        User $sender,
        Wallet $wallet,
        FeeCalculator $fee,
        float $balanceBefore,
        float $balanceAfter
    ): Transaction {
        return $this->createTransaction(
            userId: $sender->id,
            currency: $wallet->currency,
            fee: $fee,
            balanceBefore: $balanceBefore,
            balanceAfter: $balanceAfter,
            status: TransactionStatus::SUCCESS,
        );
    }

    private function createReceiverTransaction(
        int $userId,
        Currency $currency,
        float $amount,
        float $balanceBefore,
        float $balanceAfter
    ): Transaction {
        return $this->createTransaction(
            userId: $userId,
            currency: $currency,
            fee: new FeeCalculator($amount, 0, 0),
            balanceBefore: $balanceBefore,
            balanceAfter: $balanceAfter,
            status: TransactionStatus::SUCCESS,
        );
    }

    private function createTransferRecord(
        Transaction $senderTransaction,
        Wallet $senderWallet,
        Wallet $receiverWallet,
        FeeCalculator $fee,
        ?string $note
    ): Transfer {

        $metaData = [
            ...$fee->breakdown(),
            'operator' => 'Zilopay',
            'sender_name' => $senderWallet->user->name,
            'receiver_name' => $receiverWallet->user->name,
        ];

        return Transfer::create([
            'transaction_id' => $senderTransaction->id,
            'sender_wallet_id' => $senderWallet->id,
            'receiver_wallet_id' => $receiverWallet->id,
            'note' => $note,
            'metadata' => $this->buildMetadata($metaData),
        ]);
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (isset($filters['min_amount'])) {
            $query->where('amount', '>=', $filters['min_amount']);
        }

        if (isset($filters['max_amount'])) {
            $query->where('amount', '<=', $filters['max_amount']);
        }
    }

    private function logTransfer(
        Transfer $transfer,
        User $sender,
        Wallet $receiverWallet,
        float $amount,
        float $fee,
        float $senderBalanceBefore,
        float $receiverBalanceBefore,
        Wallet $senderWallet,
        Wallet $receiverWalletLocked
    ): void {
        $this->logOperation('completed', [
            'transfer_id' => $transfer->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiverWallet->user_id,
            'amount' => $amount,
            'fee' => $fee,
            'sender_balance_before' => $senderBalanceBefore,
            'sender_balance_after' => $senderWallet->balance,
            'receiver_balance_before' => $receiverBalanceBefore,
            'receiver_balance_after' => $receiverWalletLocked->balance,
        ]);
    }

    /**
     * Calculate transfer fee with configurable cap
     */
    private function calculateTransferFee(float $amount): FeeCalculator
    {
        $feePercent = config('transactions.transfer_fee_percent', 0.5);
        $fixedFee = config('transactions.transfer_fixed_fee', 0);
        $maxFee = config('transactions.max_transfer_fee', 5000);

        $percentageFee = ($amount * $feePercent) / 100;
        $cappedFee = min($percentageFee, $maxFee);

        return new FeeCalculator(
            amount: $amount,
            fixedFeedAmount: $cappedFee,
            percentageFee: $fixedFee,
        );
    }
}
