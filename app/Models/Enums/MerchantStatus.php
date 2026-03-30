<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum MerchantStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case PENDING = 0;
    case APPROVED = 1;
    case REJECTED = 2;
    case SUSPENDED = 3;

    public function label(): string
    {
        return __('enums.merchant_status.'.$this->name);
    }
}
