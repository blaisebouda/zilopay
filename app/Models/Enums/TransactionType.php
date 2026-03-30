<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum TransactionType: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case DEPOSIT = 1;
    case WITHDRAWAL = 2;
    case TRANSFER = 3;
    case PAYMENT = 4;

    public function label(): string
    {
        return __('enums.transaction_type.'.$this->name);
    }
}
