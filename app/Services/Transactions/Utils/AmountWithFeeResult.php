<?php

namespace App\Services\Transactions\Utils;

readonly class AmountWithFeeResult
{
    public function __construct(
        public float $amount,
        public float $percentage,
        public float $fixed,

    ) {}

    public function getFeeTotal(): float
    {
        return $this->percentage + $this->fixed;
    }

    public function getTotalDebit(): float
    {
        return $this->amount + $this->getFeeTotal();
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
}
