<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;
use Filament\Support\Contracts\HasColor;

enum PaymentMethodType: string implements AdvancedEnumInterface, HasColor
{
    use AdvancedEnum;

    case MOBILE_MONEY = 'mobile_money';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case CARD = 'card';

    public function label(): string
    {
        return __('enums.payment_method_type.' . $this->name);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::MOBILE_MONEY => Colors::DEFAULT,
            self::BANK_TRANSFER => Colors::SUCCESS,
            self::CASH => Colors::DEFAULT,
            self::CARD => Colors::DEFAULT,
        };
    }
}
