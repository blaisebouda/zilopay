<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum GenderEnum: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case MALE = 'male';
    case FEMALE = 'female';

    public function label(): string
    {
        return __('enums.gender.'.$this->value);
    }
}
