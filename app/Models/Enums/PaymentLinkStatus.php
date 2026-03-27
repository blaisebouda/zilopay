<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum PaymentLinkStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case ACTIVE = 1;
    case INACTIVE = 0;

    public function label(): string
    {
        return __('enums.payment_link_status.' . $this->name);
    }
}
