<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum ActivityLogAction: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case WALLET_CREATED = 'wallet_created';
    case WALLET_CREDITED = 'wallet_credited';
    case WALLET_DEBITED = 'wallet_debited';
    case WALLET_SET_DEFAULT = 'wallet_set_default';
    case WALLET_DELETED = 'wallet_deleted';

    public function label(): string
    {
        return __('enums.activity_log_action.'.$this->name);
    }
}
