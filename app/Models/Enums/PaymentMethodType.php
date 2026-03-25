<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum PaymentMethodType: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case MOBILE_MONEY = 1;
    case BANK_TRANSFER = 2;
    case CASH = 3;
    case CARD = 4;

    public function label(): string
    {
        return __('enums.payment_method_type.'.$this->value);
    }
}
