<?php

namespace App\Services\Vault;

use App\Models\Enums\Currency;
use App\Models\Enums\VaultStatus;
use App\Models\Enums\VaultTransactionType;
use App\Models\User;
use App\Models\Vault;
use App\Models\VaultTransaction;
use App\Models\Wallet;
use App\Services\Transactions\Utils\WalletValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VaultService
{
    public function __construct(
        private WalletValidator $walletValidator
    ) {}

    /**
     * Create a new vault
     */
    public function create(User $user, array $data): Vault
    {
        return Vault::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'currency' => $data['currency'] ?? Currency::XOF,
            'type' => $data['type'],
            'maturity_date' => $data['maturity_date'] ?? null,
            'amount' => 0,
            'status' => VaultStatus::ACTIVE,
        ]);
    }

    /**
     * Deposit money into vault with atomic transaction
     */
    public function deposit(Vault $vault, string $walletId, float $amount, ?string $description = null): VaultTransaction
    {
        $wallet = $this->getAndValidateWallet($walletId, $vault->user);

        if (! $vault->isActive()) {
            throw new \InvalidArgumentException('Le coffre-fort doit être actif pour effectuer un dépôt');
        }

        if ($wallet->hasInsufficientBalance($amount)) {
            throw new \InvalidArgumentException('Solde insuffisant dans le portefeuille');
        }

        return DB::transaction(function () use ($vault, $wallet, $amount, $description) {
            // Update vault balance
            $vault->credit($amount);

            // Create transaction record
            $transaction = VaultTransaction::create([
                'vault_id' => $vault->id,
                'amount' => $amount,
                'type' => VaultTransactionType::DEPOSIT,
                'description' => $description ?? 'Dépôt dans le coffre-fort',
                'metadata' => buildMetadata(),
            ]);

            // Update wallet balance
            $wallet->debit($amount);

            Log::info('Vault deposit completed', [
                'vault_id' => $vault->id,
                'transaction_id' => $transaction->id,
                'amount' => $amount,
                'wallet_id' => $wallet->id,
                'vault_balance' => $vault->amount,
                'wallet_balance' => $wallet->balance,
            ]);

            return $transaction;
        });
    }

    /**
     * Withdraw money from vault with atomic transaction
     */
    public function withdraw(Vault $vault, string $walletId, float $amount, ?string $description = null): VaultTransaction
    {
        $wallet = $this->getAndValidateWallet($walletId, $vault->user);

        if ($vault->isLocked()) {
            throw new \InvalidArgumentException('Le coffre-fort est verrouillé. Déverrouillez-le pour effectuer un retrait');
        }

        if (! $vault->hasSufficientBalance($amount)) {
            throw new \InvalidArgumentException('Solde insuffisant dans le coffre-fort');
        }

        return DB::transaction(function () use ($vault, $wallet, $amount, $description) {
            // Update vault balance
            $vault->debit($amount);

            // Create transaction record
            $transaction = VaultTransaction::create([
                'vault_id' => $vault->id,
                'amount' => $amount,
                'type' => VaultTransactionType::WITHDRAWAL,
                'description' => $description ?? 'Retrait VaultService coffre-fort',
                'metadata' => buildMetadata(),
            ]);

            // Add balance to wallet
            $wallet->credit($amount);

            Log::info('Vault withdrawal completed', [
                'vault_id' => $vault->id,
                'transaction_id' => $transaction->id,
                'amount' => $amount,
                'wallet_id' => $wallet->id,
                'vault_balance' => $vault->amount,
                'wallet_balance' => $wallet->balance,
            ]);

            return $transaction;
        });
    }

    /**
     * Toggle vault lock status
     */
    public function toggleLock(Vault $vault): Vault
    {
        $vault->toggleLock();

        Log::info('Vault lock toggled', [
            'vault_id' => $vault->id,
            'new_status' => $vault->status->value,
        ]);

        return $vault;
    }

    /**
     * Get vault with transactions
     */
    public function getVaultWithTransactions(Vault $vault, int $limit = 10): Vault
    {
        return $vault->load(['transactions' => function ($query) use ($limit) {
            $query->latest()->limit($limit);
        }]);
    }

    /**
     * Get user's vaults
     */
    public function getUserVaults(User $user)
    {
        return $user->vaults()->latest()->get();
    }

    private function getAndValidateWallet(string $walletId, User $user): Wallet
    {
        $wallet = Wallet::findByCode($walletId);
        $this->walletValidator->validateOwnership($user, $wallet->code);

        return $wallet;
    }
}
