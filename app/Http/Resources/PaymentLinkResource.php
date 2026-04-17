<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Enums\LockActiveStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'merchant_id' => $this->merchant_id,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount ? (float) $this->amount : null,
            'currency' => $this->currency,
            'status' => $this->status,
            'status_label' => LockActiveStatus::from($this->status)->label(),
            'max_uses' => $this->max_uses,
            'uses_count' => $this->uses_count,
            'remaining_uses' => $this->max_uses ? $this->max_uses - $this->uses_count : null,
            'expires_at' => $this->expires_at?->toIso8601String(),
            'is_expired' => $this->expires_at && now()->greaterThan($this->expires_at),
            'metadata' => $this->metadata,
            'payment_url' => url("/merchant/pay/{$this->uuid}"),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
