<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

class AuthService
{
    public static function findUserByIdentifier(string $identifier): User
    {
        $user = User::where('email', $identifier)
            ->orWhere('phone_number', $identifier)
            ->firstOrFail();

        return $user;
    }
}
