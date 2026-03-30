<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnum;
use App\Models\Enums\Contracts\AdvancedEnumInterface;

enum OtpType: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case REGISTRATION = 'registration';
    case LOGIN = 'login';
    case PASSWORD_RESET = 'password_reset';
    case PHONE_VERIFICATION = 'phone_verification';

    public function label(): string
    {
        return __('enums.otp_type.'.$this->name);
    }
}
