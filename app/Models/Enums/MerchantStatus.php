<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum MerchantStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case PENDING = 0;
    case APPROVED = 1;
    case REJECTED = 2;


    public function label(): string
    {
        return __('enums.merchant_status.' . $this->name);
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => Colors::WARNING,
            self::APPROVED => Colors::SUCCESS,
            self::REJECTED => Colors::FAILED,
        };
    }
}
