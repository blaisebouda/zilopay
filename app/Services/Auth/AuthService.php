<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

class AuthService
{
    public function __construct(private OtpService $otpService) {}

    public static  function findUserByIdentifier(string $identifier): User
    {
        $user = User::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->firstOrFail();

        return $user;
    }
}
