<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum PaymentMethodCode: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case ORANGE_MONEY = 'orange_money';
    case MOOV_MONEY = 'moov_money';
    case WAVE = 'wave';
    case TELECEL_MONEY = 'telecel_money';
    case BANK_TRANSFER = 'bank_transfer';

    public function label(): string
    {
        return __('enums.payment_method_code.'.$this->name);
    }
}
