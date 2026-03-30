<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum PaymentMethodType: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case MOBILE_MONEY = 'mobile_money';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case CARD = 'card';

    public function label(): string
    {
        return __('enums.payment_method_type.'.$this->name);
    }
}
