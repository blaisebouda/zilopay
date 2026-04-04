<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantApiKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'merchant_id' => $this->merchant_id,
            'name' => $this->name,
            'key' => $this->key,
            'public_key' => $this->public_key,
            'is_live' => (bool) $this->is_live,
            'is_active' => (bool) $this->is_active,
            'last_used_at' => $this->last_used_at?->toIso8601String(),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        if ($this->plain_secret) {
            $data['secret'] = $this->plain_secret;
            $data['secret_warning'] = 'This secret will only be displayed once. Please save it securely.';
        }

        return $data;
    }
}
