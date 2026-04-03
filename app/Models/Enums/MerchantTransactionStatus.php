<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum MerchantTransactionStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case PENDING   = 0;
    case SETTLED   = 1;
    case REFUNDED  = 2;
    case DISPUTED  = 3;

    public function label(): string
    {
        return __('enums.merchant_transaction_status.' . $this->name);
    }
}
