<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;

class WalletService
{
    /**
     * Create a default wallet for a new user (XOF currency).
     */
    public static function createDefaultWallet(User $user): ?Wallet
    {
        $defaultCurrency = Currency::default()->first();

        if (! $defaultCurrency) {
            return null;
        }

        return Wallet::create([
            'user_id' => $user->id,
            'currency_id' => $defaultCurrency->id,
            'balance' => 0,
            'is_default' => true,
        ]);
    }

    /**
     * Create a wallet for a specific currency.
     */
    public static function createWallet(User $user, int $currencyId): Wallet
    {
        $existingWallet = Wallet::where('user_id', $user->id)
            ->where('currency_id', $currencyId)
            ->first();

        if ($existingWallet) {
            throw new \Exception('Wallet already exists for this currency');
        }

        return Wallet::create([
            'user_id' => $user->id,
            'currency_id' => $currencyId,
            'balance' => 0,
            'is_default' => false,
        ]);
    }

    /**
     * Set a wallet as default for the user.
     */
    public static function setDefaultWallet(Wallet $wallet): void
    {
        Wallet::where('user_id', $wallet->user_id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $wallet->is_default = true;
        $wallet->save();
    }
}
