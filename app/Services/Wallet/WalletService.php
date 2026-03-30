<?php

namespace App\Services\Wallet;

use App\Models\Enums\Currency;
use App\Models\User;
use App\Models\Wallet;

class WalletService
{
    /**
     * Create a default wallet for a new user (XOF currency).
     */
    public static function createDefaultWallet(User $user): ?Wallet
    {

        return Wallet::create([
            'user_id' => $user->id,
            'currency' => Currency::XOF->value,
            'balance' => 0,
            'is_default' => true,
        ]);
    }

    /**
     * Create a wallet for a specific currency.
     */
    public static function createWallet(User $user, Currency $currency): Wallet
    {
        $existingWallet = Wallet::where('user_id', $user->id)
            ->where('currency', $currency->value)
            ->first();

        if ($existingWallet) {
            throw new \Exception('Wallet already exists for this currency');
        }

        return Wallet::create([
            'user_id' => $user->id,
            'currency' => $currency->value,
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
