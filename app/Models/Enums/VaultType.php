<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum VaultType: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case SAVINGS = 'savings';
    case INVESTMENT = 'investment';
    case EMERGENCY = 'emergency';
    case IMPREVUS = 'imprevus';
    case SANTE = 'sante';
    case FACTURES = 'factures';
    case FETES = 'fetes';
    case ETUDES = 'etudes';
    case BUSINESS = 'business';
    case VOYAGE = 'voyage';
    case TONTINE = 'tontine';
    case AUTRES = 'autres';

    public function label(): string
    {
        return __('enums.vault_type.'.$this->name);
    }

    public function color(): string
    {
        return match ($this) {
            self::SAVINGS => Colors::SUCCESS,
            self::INVESTMENT => Colors::DEFAULT,
            self::EMERGENCY => Colors::DESTRUCTIVE,
            default => Colors::DEFAULT,
        };
    }
}
