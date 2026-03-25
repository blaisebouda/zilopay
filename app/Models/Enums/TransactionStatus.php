<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum TransactionStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case PENDING = 1;
    case SUCCESS = 2;
    case REFUND = 3;
    case BLOCKED = 4;
    case CANCELLED = 5;
    case FAILED = 6;

    public function label(): string
    {
        return __('enums.transaction_status.'.$this->value);
    }
}
