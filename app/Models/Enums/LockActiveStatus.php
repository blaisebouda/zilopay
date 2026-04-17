<?php

namespace App\Models\Enums;

use App\Constants\Colors;
use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;
use Filament\Support\Contracts\HasColor;

enum LockActiveStatus: int implements AdvancedEnumInterface, HasColor
{
    use AdvancedEnum;

    case LOCKED = 0;
    case ACTIVE = 1;

    public function getLabel(): ?string
    {
        return $this->label();
    }

    public function getColor(): string
    {
        return match ($this) {
            self::LOCKED => Colors::FAILED,
            self::ACTIVE => Colors::SUCCESS,
        };
    }

    public function label(): string
    {
        return __('enums.lock_active_status.'.$this->name);
    }
}
