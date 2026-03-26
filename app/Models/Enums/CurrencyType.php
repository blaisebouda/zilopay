<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum CurrencyType: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case FIAT = 'fiat';
    case CRYPTO = 'crypto';

    public function label(): string
    {
        return __('enums.currency_type.' . $this->name);
    }
}
