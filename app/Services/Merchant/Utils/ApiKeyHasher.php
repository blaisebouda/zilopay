<?php

declare(strict_types=1);

namespace App\Services\Merchant\Utils;

use Illuminate\Support\Facades\Hash;

class ApiKeyHasher
{
    /**
     * Generate a new API key pair.
     *
     * @return object{key: string, publicKey: string, secret: string, plainSecret: string}
     */
    public static function generateKeyPair(bool $isLive = false): object
    {
        $prefix = $isLive ? 'mk_live_' : 'mk_test_';
        $publicPrefix = $isLive ? 'mk_pub_live_' : 'mk_pub_test_';

        $key = $prefix.self::generateRandomString(32);
        $publicKey = $publicPrefix.self::generateRandomString(32);
        $plainSecret = self::generateRandomString(48);
        $hashedSecret = self::hashSecret($plainSecret);

        return (object) [
            'key' => $key,
            'publicKey' => $publicKey,
            'secret' => $hashedSecret,
            'plainSecret' => $plainSecret,
        ];
    }

    /**
     * Hash a secret for database storage.
     */
    public static function hashSecret(string $secret): string
    {
        return hash('sha256', $secret);
    }

    /**
     * Verify a secret against a hashed value.
     */
    public static function verifySecret(string $secret, string $hashedSecret): bool
    {
        return hash_equals(hash('sha256', $secret), $hashedSecret);
    }

    /**
     * Generate a random string.
     */
    private static function generateRandomString(int $length): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}
