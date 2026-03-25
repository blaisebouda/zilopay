<?php

namespace App\Services\Transactions\Utils;

use App\Models\PaymentMethod;

class AmountValidator
{
    /**
     * Validate amount is positive
     *
     * @throws \InvalidArgumentException
     */
    public function validatePositive(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }
    }

    /**
     * Validate amount against payment method limits
     *
     * @throws \InvalidArgumentException
     */
    public function validateAgainstPaymentMethod(float $amount, PaymentMethod $paymentMethod): void
    {
        $this->validatePositive($amount);

        if ($amount < $paymentMethod->min_amount) {
            throw new \InvalidArgumentException(
                sprintf('Amount must be at least %.2f', $paymentMethod->min_amount)
            );
        }

        if ($amount > $paymentMethod->max_amount) {
            throw new \InvalidArgumentException(
                sprintf('Amount must not exceed %.2f', $paymentMethod->max_amount)
            );
        }
    }

    /**
     * Validate transfer amount against config limits
     *
     * @throws \InvalidArgumentException
     */
    public function validateTransferAmount(float $amount): void
    {
        $this->validatePositive($amount);

        $minTransfer = config('transactions.min_transfer', 100);
        $maxTransfer = config('transactions.max_transfer', 10000000);

        if ($amount < $minTransfer) {
            throw new \InvalidArgumentException(sprintf('Minimum transfer amount is %.2f', $minTransfer));
        }

        if ($amount > $maxTransfer) {
            throw new \InvalidArgumentException(sprintf('Maximum transfer amount is %.2f', $maxTransfer));
        }
    }
}
