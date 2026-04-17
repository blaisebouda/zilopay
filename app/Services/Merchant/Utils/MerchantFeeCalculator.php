<?php

declare(strict_types=1);

namespace App\Services\Merchant\Utils;

use App\Models\Merchant;

class MerchantFeeCalculator
{
    /**
     * Calculate fees for a given amount.
     *
     * @return object{net_amount: float, fee_amount: float, total_amount: float}
     */
    public static function calculate(float $amount, Merchant $merchant): object
    {
        $fixedFee = (float) $merchant->fee_fixed;
        $percentageFee = (float) $merchant->fee_percent;

        $percentageAmount = $amount * ($percentageFee / 100);
        $totalFee = $fixedFee + $percentageAmount;
        $netAmount = $amount - $totalFee;

        return (object) [
            'net_amount' => round($netAmount, 8),
            'fee_amount' => round($totalFee, 8),
            'total_amount' => round($amount + $totalFee, 8),
        ];
    }

    /**
     * Calculate the amount needed to receive a specific net amount after fees.
     */
    public static function calculateGrossAmount(float $netAmount, Merchant $merchant): float
    {
        $fixedFee = (float) $merchant->fee_fixed;
        $percentageFee = (float) $merchant->fee_percent;

        $grossAmount = ($netAmount + $fixedFee) / (1 - ($percentageFee / 100));

        return round($grossAmount, 8);
    }

    /**
     * Get fee breakdown for display.
     *
     * @return array<string, mixed>
     */
    public static function getFeeBreakdown(float $amount, Merchant $merchant): array
    {
        $calculation = self::calculate($amount, $merchant);

        return [
            'original_amount' => $amount,
            'fixed_fee' => (float) $merchant->fee_fixed,
            'percentage_fee' => (float) $merchant->fee_percent,
            'percentage_fee_amount' => round($amount * ($merchant->fee_percent / 100), 8),
            'total_fee' => $calculation['fee_amount'],
            'net_amount' => $calculation['net_amount'],
        ];
    }
}
