<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Enums\MerchantStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
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
            'business_name' => $this->business_name,
            'business_email' => $this->business_email,
            'phone' => $this->phone,
            'country' => $this->country,
            'fee_fixed' => (float) $this->fee_fixed,
            'fee_percentage' => (float) $this->fee_percentage,
            'status' => $this->status,
            'status_label' => MerchantStatus::from($this->status)->label(),
            'approved_at' => $this->approved_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
