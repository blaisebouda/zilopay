<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum VaultStatus: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case ACTIVE = 'active';
    case LOCKED = 'locked';
    case MATURED = 'matured';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Actif',
            self::LOCKED => 'Verrouillé',
            self::MATURED => 'Mûr',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => Colors::SUCCESS,
            self::LOCKED => Colors::DESTRUCTIVE,
            self::MATURED => Colors::WARNING,
        };
    }
}
