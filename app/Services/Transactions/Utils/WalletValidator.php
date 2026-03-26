<?php

namespace App\Services\Transactions\Utils;

use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Wallet;

class WalletValidator
{
    /**
     * Validate wallet belongs to user
     *
     * @throws \InvalidArgumentException
     */
    public function validateOwnership(User $user, string $walletCode): Wallet
    {
        $wallet = Wallet::where('code', $walletCode)
            ->where('user_id', $user->id)
            ->first();

        if (! $wallet) {
            throw new \InvalidArgumentException('Wallet not found or does not belong to user');
        }

        return $wallet;
    }

    /**
     * Validate wallet exists (for receiver wallets)
     *
     * @throws \InvalidArgumentException
     */
    public function validateExists(string $walletCode): Wallet
    {
        /** @var Wallet|null $wallet */
        $wallet = Wallet::where('code', $walletCode)->first();

        if (! $wallet) {
            throw new \InvalidArgumentException('Wallet not found');
        }

        return $wallet;
    }

    /**
     * Validate sender and receiver are different
     *
     * @throws \InvalidArgumentException
     */
    public function validateDifferentWallets(Wallet $senderWallet, Wallet $receiverWallet): void
    {
        if ($senderWallet->user_id === $receiverWallet->user_id) {
            throw new \InvalidArgumentException('Cannot transfer to your own wallet');
        }
    }

    /**
     * Validate same currency between wallets
     *
     * @throws \InvalidArgumentException
     */
    public function validateSameCurrency(Wallet $wallet1, Wallet $wallet2): void
    {
        if ($wallet1->currency_id !== $wallet2->currency_id) {
            throw new \InvalidArgumentException('Cross-currency transfers are not supported');
        }
    }

    /**
     * Validate sufficient balance
     *
     * @throws \InvalidArgumentException
     */
    public function validateSufficientBalance(Wallet $wallet, float $requiredAmount): void
    {
        if ($wallet->hasInsufficientBalance($requiredAmount)) {
            throw new \InvalidArgumentException(
                sprintf('Insufficient balance. Required: %.2f, Available: %.2f', $requiredAmount, $wallet->balance)
            );
        }
    }

    /**
     * Validate payment method exists
     *
     * @throws \InvalidArgumentException
     */
    public function validatePaymentMethod(int $paymentMethodId): PaymentMethod
    {
        /** @var PaymentMethod|null $paymentMethod */
        $paymentMethod = PaymentMethod::find($paymentMethodId);

        if (! $paymentMethod) {
            throw new \InvalidArgumentException('Payment method not found');
        }

        return $paymentMethod;
    }
}
