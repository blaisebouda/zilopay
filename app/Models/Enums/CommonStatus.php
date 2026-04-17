<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;
use Filament\Support\Contracts\HasColor;

enum CommonStatus: int implements AdvancedEnumInterface, HasColor
{
    use AdvancedEnum;

    case INACTIVE = 0;
    case ACTIVE = 1;


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
        return __('enums.common_status.' . $this->name);
    }
}
