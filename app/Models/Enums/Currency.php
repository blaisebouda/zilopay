<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum Currency: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case XOF = 'XOF';
    case USD = 'USD';
    case EUR = 'EUR';

    public function label(): string
    {
        return match ($this) {
            self::XOF => 'Franc CFA (BCEAO)',
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::XOF => 'CFA',
            self::USD => '$',
            self::EUR => '€',
        };
    }
}
