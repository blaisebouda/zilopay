<?php

use App\Models\Currency;
use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Transactions\TransferService;
use App\Services\Transactions\Utils\AmountValidator;
use App\Services\Transactions\Utils\FeeCalculator;
use App\Services\Transactions\Utils\WalletValidator;

beforeEach(function () {
    $this->currency = Currency::factory()->create();

    /** @var User $sender */
    $this->sender = User::factory()->create();
    /** @var User $receiver */
    $this->receiver = User::factory()->create();

    /** @var Wallet $senderWallet */
    $this->senderWallet = Wallet::factory()->create([
        'user_id' => $this->sender->id,
        'currency_id' => $this->currency->id,
        'balance' => 10000.00,
    ]);

    /** @var Wallet $receiverWallet */
    $this->receiverWallet = Wallet::factory()->create([
        'user_id' => $this->receiver->id,
        'currency_id' => $this->currency->id,
        'balance' => 100.00,
    ]);

    // Mock or use real services
    $this->walletValidator = new WalletValidator;
    $this->amountValidator = new AmountValidator;
    $this->feeCalculator = new FeeCalculator;

    $this->transferService = new TransferService(
        $this->walletValidator,
        $this->amountValidator,
        $this->feeCalculator
    );
});

describe('create', function () {
    it('successfully transfers money between users', function () {
        $amount = 1000.00;
        $initialSenderBalance = $this->senderWallet->balance;
        $initialReceiverBalance = $this->receiverWallet->balance;

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => $amount,
            'note' => 'Test transfer',
        ];

        $transaction = $this->transferService->create($this->sender, $data);

        // Assert transaction is created correctly
        expect($transaction)->toBeInstanceOf(Transaction::class);
        expect($transaction->transaction_type)->toBe(TransactionType::TRANSFER);
        expect($transaction->status)->toBe(TransactionStatus::SUCCESS);
        expect($transaction->user_id)->toBe($this->sender->id);

        // Assert sender wallet debited
        $this->senderWallet->refresh();
        expect($this->senderWallet->balance)->toBeLessThan($initialSenderBalance);

        // Assert receiver wallet credited
        $this->receiverWallet->refresh();
        expect($this->receiverWallet->balance)->toBe($initialReceiverBalance + $amount);

        // Assert transfer record created
        $transfer = Transfer::where('transaction_id', $transaction->id)->first();
        expect($transfer)->not->toBeNull();
        expect($transfer->sender_wallet_id)->toBe($this->senderWallet->id);
        expect($transfer->receiver_wallet_id)->toBe($this->receiverWallet->id);
        expect($transfer->note)->toBe('Test transfer');
    });

    it('creates receiver transaction record', function () {
        $amount = 500.00;

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => $amount,
        ];

        $senderTransaction = $this->transferService->create($this->sender, $data);

        // Check receiver transaction exists
        $receiverTransaction = Transaction::where('user_id', $this->receiver->id)
            ->where('transaction_type', TransactionType::TRANSFER)
            ->where('amount', $amount)
            ->first();

        expect($receiverTransaction)->not->toBeNull();
        expect($receiverTransaction->status)->toBe(TransactionStatus::SUCCESS);
    });

    it('throws exception for same wallet transfer', function () {
        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->senderWallet->id,
            'amount' => 100.00,
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Cannot transfer to your own wallet');
    });

    it('throws exception for cross-currency transfer', function () {
        $otherCurrency = Currency::factory()->create();
        $receiverOtherWallet = Wallet::factory()->create([
            'user_id' => $this->receiver->id,
            'currency_id' => $otherCurrency->id,
            'balance' => 100.00,
        ]);

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $receiverOtherWallet->id,
            'amount' => 100.00,
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Cross-currency transfers are not supported');
    });

    it('throws exception for insufficient balance', function () {
        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 50000.00, // More than balance
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Insufficient balance');
    });

    it('throws exception for invalid sender wallet', function () {
        $otherUser = User::factory()->create();
        $otherWallet = Wallet::factory()->create([
            'user_id' => $otherUser->id,
            'currency_id' => $this->currency->id,
        ]);

        $data = [
            'sender_wallet_id' => $otherWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 100.00,
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Wallet not found or does not belong to user');
    });

    it('throws exception for non-existent receiver wallet', function () {
        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => 99999,
            'amount' => 100.00,
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Wallet not found');
    });

    it('throws exception for amount below minimum', function () {
        config(['transactions.min_transfer' => 100]);

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 50.00,
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Minimum transfer amount is');
    });

    it('throws exception for amount above maximum', function () {
        config(['transactions.max_transfer' => 1000]);

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 5000.00,
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Maximum transfer amount is');
    });

    it('throws exception for negative amount', function () {
        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => -100.00,
        ];

        expect(fn () => $this->transferService->create($this->sender, $data))
            ->toThrow(\InvalidArgumentException::class, 'Amount must be greater than 0');
    });

    it('applies transfer fee correctly', function () {
        config(['transactions.transfer_fee_percent' => 1.0]); // 1% fee
        config(['transactions.max_transfer_fee' => 100]); // Cap at 100

        $amount = 1000.00;
        $expectedFee = 10.00; // 1% of 1000

        $initialSenderBalance = $this->senderWallet->balance;

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => $amount,
        ];

        $this->transferService->create($this->sender, $data);

        $this->senderWallet->refresh();
        $expectedSenderBalance = $initialSenderBalance - $amount - $expectedFee;

        expect($this->senderWallet->balance)->toBe($expectedSenderBalance);
    });

    it('caps transfer fee at maximum', function () {
        config(['transactions.transfer_fee_percent' => 10.0]); // 10% fee
        config(['transactions.max_transfer_fee' => 50]); // But cap at 50

        $amount = 1000.00;
        $expectedFee = 50.00; // Capped, not 100

        $initialSenderBalance = $this->senderWallet->balance;

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => $amount,
        ];

        $this->transferService->create($this->sender, $data);

        $this->senderWallet->refresh();
        $expectedSenderBalance = $initialSenderBalance - $amount - $expectedFee;

        expect($this->senderWallet->balance)->toBe($expectedSenderBalance);
    });

    it('stores balance_before and balance_after in transaction', function () {
        $amount = 500.00;
        $initialBalance = $this->senderWallet->balance;
        $defaultFeePercent = config('transactions.transfer_fee_percent', 0.5);
        $expectedFee = ($amount * $defaultFeePercent) / 100;

        $data = [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => $amount,
        ];

        $transaction = $this->transferService->create($this->sender, $data);

        expect($transaction->balance_before)->toBe($initialBalance);
        expect($transaction->balance_after)->toBe($initialBalance - $amount - $expectedFee);
    });
});

describe('confirm', function () {
    it('throws BadMethodCallException', function () {
        expect(fn () => $this->transferService->confirm('uuid', []))
            ->toThrow(\BadMethodCallException::class, 'Transfers are completed immediately and cannot be confirmed');
    });
});

describe('cancel', function () {
    it('throws BadMethodCallException', function () {
        expect(fn () => $this->transferService->cancel('uuid', 'reason'))
            ->toThrow(\BadMethodCallException::class, 'Transfers cannot be cancelled');
    });
});

describe('getTransferHistory', function () {
    it('returns transfers for a user as sender', function () {
        // Create some transfers
        $this->transferService->create($this->sender, [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 100.00,
        ]);

        $history = $this->transferService->getTransferHistory($this->sender);

        expect($history)->toHaveCount(1);
        expect($history->first()->transaction_type)->toBe(TransactionType::TRANSFER);
    });

    it('returns transfers for a user as receiver', function () {
        $this->transferService->create($this->sender, [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 100.00,
        ]);

        $history = $this->transferService->getTransferHistory($this->receiver);

        expect($history)->toHaveCount(1);
    });

    it('filters by date range', function () {
        $this->transferService->create($this->sender, [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 100.00,
        ]);

        $filters = [
            'from_date' => now()->subDay()->format('Y-m-d'),
            'to_date' => now()->addDay()->format('Y-m-d'),
        ];

        $history = $this->transferService->getTransferHistory($this->sender, $filters);

        expect($history)->toHaveCount(1);
    });

    it('filters by amount range', function () {
        $this->transferService->create($this->sender, [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 500.00,
        ]);

        $filters = [
            'min_amount' => 400.00,
            'max_amount' => 600.00,
        ];

        $history = $this->transferService->getTransferHistory($this->sender, $filters);

        expect($history)->toHaveCount(1);
    });

    it('excludes transfers outside amount range', function () {
        $this->transferService->create($this->sender, [
            'sender_wallet_id' => $this->senderWallet->id,
            'receiver_wallet_id' => $this->receiverWallet->id,
            'amount' => 100.00,
        ]);

        $filters = [
            'min_amount' => 200.00,
            'max_amount' => 600.00,
        ];

        $history = $this->transferService->getTransferHistory($this->sender, $filters);

        expect($history)->toHaveCount(0);
    });
});
