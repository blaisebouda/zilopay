<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum Country: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case BF = 'BF';
    case SN = 'SN';
    case CI = 'CI';

    public function label(): string
    {
        return match ($this) {
            self::BF => 'Burkina Faso',
            self::SN => 'Sénégal',
            self::CI => 'Côte d\'Ivoire',
        };
    }

    public function phoneCode(): string
    {
        return match ($this) {
            self::BF => '226',
            self::SN => '221',
            self::CI => '225',
        };
    }
}
