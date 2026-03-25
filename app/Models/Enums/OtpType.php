<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;


enum OtpType :string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    Case RE registration,
    login,
    password_reset,
    phone_verification


    public function label(): string
    {
        return __('enums.otp_type.'.$this->value);
    }
}
