<?php

namespace App\Utils;

use App\Models\PaymentMethod;

readonly class FeeCalculator
{

    public function __construct(
        public float $originalAmount,
        public float $percentageFee,
        public float $fixedFeed,

    ) {}

    public function getFeeTotal(): float
    {
        return $this->percentage + $this->fixed;
    }

    public function getGrossAmount(): float
    {
        return $this->amount + $this->getFeeTotal();
    }

    public function getNetAmount(): float
    {
        return $this->amount - $this->getFeeTotal();
    }

    /**
     * Get fee breakdown as array
     */
    public function toArray(): array
    {
        return [
            'percentage' => $this->percentage,
            'fixed' => $this->fixed,
            'feeTotal' => $this->getFeeTotal(),
            'totalDebit' => $this->getTotalDebit(),
        ];
    }

    /**
     * Calculate fees based on payment method
     */
    public function calculate(float $amount, PaymentMethod $paymentMethod): AmountWithFeeResult
    {
        $percentageFee = ($amount * $paymentMethod->fee_percent) / 100;
        $fixedFee = $paymentMethod->fee_fixed;

        return new AmountWithFeeResult(
            amount: $amount,
            percentage: $percentageFee,
            fixed: $fixedFee,
        );
    }

    /**
     * Calculate transfer fee with configurable cap
     */
    public function calculateTransferFee(float $amount): AmountWithFeeResult
    {
        $feePercent = config('transactions.transfer_fee_percent', 0.5);
        $fixedFee = config('transactions.transfer_fixed_fee', 0);
        $maxFee = config('transactions.max_transfer_fee', 5000);

        $percentageFee = ($amount * $feePercent) / 100;
        $cappedFee = min($percentageFee, $maxFee);

        return new AmountWithFeeResult(
            amount: $amount,
            percentage: $cappedFee,
            fixed: $fixedFee,
        );
    }
}
