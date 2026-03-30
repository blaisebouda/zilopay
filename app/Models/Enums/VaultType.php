<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum VaultType: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case SAVINGS = 'savings';
    case INVESTMENT = 'investment';
    case EMERGENCY = 'emergency';

    public function label(): string
    {
        return match ($this) {
            self::SAVINGS => 'Épargne',
            self::INVESTMENT => 'Investissement',
            self::EMERGENCY => 'Urgence',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SAVINGS => '#28a745',
            self::INVESTMENT => '#007bff',
            self::EMERGENCY => '#dc3545',
        };
    }
}
