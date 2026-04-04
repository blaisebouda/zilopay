<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Merchant;
use App\Models\MerchantApiKey;
use App\Services\Merchant\Utils\ApiKeyHasher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MerchantApiKeyService
{
    /**
     * Create a new API key for merchant.
     *
     * @param  array<string, mixed>  $data
     * @return object{api_key: MerchantApiKey, plain_secret: string}
     */
    public function create(Merchant $merchant, array $data): object
    {
        $keyPair = ApiKeyHasher::generateKeyPair($data['is_live'] ?? false);

        $apiKey = MerchantApiKey::create([
            'merchant_id' => $merchant->id,
            'name' => $data['name'],
            'key' => $keyPair->key,
            'public_key' => $keyPair->publicKey,
            'secret' => $keyPair->secret,
            'is_live' => $data['is_live'] ?? false,
            'is_active' => true,
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        return (object) [
            'api_key' => $apiKey->fresh(),
            'plain_secret' => $keyPair->plainSecret,
        ];
    }

    /**
     * Get API key by UUID.
     *
     * @throws ModelNotFoundException
     */
    public function getByUuid(string $uuid): MerchantApiKey
    {
        return MerchantApiKey::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Get all API keys for a merchant.
     *
     * @return Collection<int, MerchantApiKey>
     */
    public function getAllForMerchant(Merchant $merchant): Collection
    {
        return $merchant->apiKeys()->latest()->get();
    }

    /**
     * Revoke an API key.
     */
    public function revoke(MerchantApiKey $apiKey): void
    {
        $apiKey->is_active = false;
        $apiKey->save();
    }

    /**
     * Delete an API key.
     */
    public function delete(MerchantApiKey $apiKey): void
    {
        $apiKey->delete();
    }

    /**
     * Verify an API key and secret.
     */
    public function verify(string $key, string $secret): ?MerchantApiKey
    {
        $apiKey = MerchantApiKey::where('key', $key)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $apiKey) {
            return null;
        }

        if (! ApiKeyHasher::verifySecret($secret, $apiKey->secret)) {
            return null;
        }

        $apiKey->update(['last_used_at' => now()]);

        return $apiKey;
    }
}
