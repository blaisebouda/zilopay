<?php

namespace App\Services\Transactions\Utils;

use App\Models\PaymentMethod;

class FeeCalculator
{
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
