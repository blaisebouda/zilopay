<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum DocumentType: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case ID_CARD = 'id_card';
    case BUSINESS_LICENSE = 'business_license';
    case TAX_CERTIFICATE = 'tax_certificate';

    public function label(): string
    {
        return __('enums.document_type.' . $this->name);
    }
}
