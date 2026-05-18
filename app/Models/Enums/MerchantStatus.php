<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum MerchantStatus: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return __('enums.merchant_status.' . $this->name);
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => Colors::WARNING,
            self::APPROVED => Colors::SUCCESS,
            self::REJECTED => Colors::DANGER,
        };
    }
}
