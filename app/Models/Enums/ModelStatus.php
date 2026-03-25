<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum ModelStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case ACTIVE = 1;
    case INACTIVE = 0;

    public function getLabel(): ?string
    {
        return $this->label();
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
        };
    }

    public function label(): string
    {
        return __('enums.model_status.'.$this->value);
    }
}
