<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum UserRole: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case ADMIN = 'admin';
    case USER = 'user';
    case MERCHANT = 'merchant';

    public function label(): string
    {
        return __('enums.user_role.' . $this->name);
    }
}
