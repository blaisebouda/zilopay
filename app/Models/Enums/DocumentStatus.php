<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum DocumentStatus: int implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case PENDING  = 0;
    case APPROVED = 1;
    case REJECTED = 2;

    public function label(): string
    {
        return __('enums.document_status.' . $this->name);
    }
}
