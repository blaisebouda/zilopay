<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum CommonStatus: int implements AdvancedEnumInterface
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
            self::ACTIVE => Colors::SUCCESS,
            self::INACTIVE => Colors::FAILED,
        };
    }

    public function label(): string
    {
        return __('enums.model_status.' . $this->name);
    }
}
