<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum VaultTransactionType: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';

    public function label(): string
    {
        return match ($this) {
            self::DEPOSIT => 'Dépôt',
            self::WITHDRAWAL => 'Retrait',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DEPOSIT => '#28a745',
            self::WITHDRAWAL => '#dc3545',
        };
    }
}
