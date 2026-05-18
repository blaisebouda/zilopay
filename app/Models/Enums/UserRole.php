<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;
use Filament\Support\Contracts\HasColor;

enum UserRole: string implements AdvancedEnumInterface, HasColor
{
    use AdvancedEnum;

    case ADMIN = 'admin';
    case USER = 'user';
    case MERCHANT = 'merchant';

    public function label(): string
    {
        return __('enums.user_role.'.$this->name);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ADMIN => Colors::SUCCESS,
            self::USER => Colors::DEFAULT,
            self::MERCHANT => Colors::WARNING,
        };
    }
}
