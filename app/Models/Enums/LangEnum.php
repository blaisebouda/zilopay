<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum LangEnum: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case FR = 'fr';
    case EN = 'en';

    public function label(): string
    {
        return __('enums.lang.'.$this->name);
    }
}
