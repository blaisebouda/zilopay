<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum MerchantTransactionStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case PENDING = 0;
    case SUCCESS = 1;
    case CANCELLED = 2;
    case FAILED = 3;
    case REFUNDED = 4;

    public function label(): string
    {
        return __('enums.merchant_transaction_status.' . $this->name);
    }
}
