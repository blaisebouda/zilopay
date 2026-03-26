<?php

namespace App\Models\Enums;

use App\Constants\Colors;
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
        return __('enums.transaction_status.' . $this->name);
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => Colors::WARNING,
            self::SUCCESS => Colors::SUCCESS,
            self::REFUND => Colors::FAILED,
            self::BLOCKED => Colors::FAILED,
            self::CANCELLED => Colors::FAILED,
            self::FAILED => Colors::FAILED,
        };
    }
}
