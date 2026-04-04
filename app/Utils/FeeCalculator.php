<?php

namespace App\Utils;

readonly class FeeCalculator
{
    public function __construct(
        private float $amount,
        private float $fixedFeedAmount,
        private float $percentageFee,

    ) {}

    public static function make(float $amount, float $fixedFeedAmount, float $percentageFee): self
    {
        return new self(
            $amount,
            $fixedFeedAmount,
            $percentageFee
        );
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPercentageFeeAmount(): float
    {
        $amount = $this->amount * ($this->percentageFee / 100);

        return roundAmount($amount);
    }

    public function getPlatformFeeAmount(): float
    {
        $amount = $this->fixedFeedAmount + $this->getPercentageFeeAmount();

        return roundAmount($amount);
    }

    public function getNetAmount(): float
    {
        $netAmount = $this->amount - $this->getPlatformFeeAmount();

        return roundAmount($netAmount);
    }

    public function getTotalDebit(): float
    {
        return $this->amount + $this->getPlatformFeeAmount();
    }

    /**
     * Calculate fees for a given amount.
     *
     * @return array{
     * amount: float,
     * net_amount: float,
     * platform_fee_amount: float,
     * percentage_fee_amount: float,
     * fixed_fee: float,
     * percentage_fee: float
     * }
     */
    public function breakdown(): array
    {

        return [
            'amount' => $this->amount,
            'net_amount' => $this->getNetAmount(),
            'platform_fee_amount' => $this->getPlatformFeeAmount(),
            'percentage_fee_amount' => $this->getPercentageFeeAmount(),
            'fixed_fee' => $this->fixedFeedAmount,
            'percentage_fee' => $this->percentageFee,
        ];
    }
}
