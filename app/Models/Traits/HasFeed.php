<?php

namespace App\Models\Traits;

trait HasFeed
{
    public function feePercentLabel()
    {
        return $this->fee_percent . ' %';
    }

    public function feeFixedLabel()
    {
        return format_amount($this->fee_fixed, $this->currency?->symbol() ?? 'XOF');
    }
}
