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
            'uuid' => $this->uuid,
            'business_name' => $this->business_name,
            'business_email' => $this->business_email,
            'phone_number' => $this->phone_number,
            'country' => $this->country->label(),
            'fee_fixed' => (float) $this->fee_fixed,
            'fee_percentage' => (float) $this->fee_percentage,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'approved_at' => $this->approved_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'documents' => $this->documents->map(fn($doc) => [
                'id' => $doc->id,
                'type' => $doc->type,
                'type_label' => $doc->type->label(),
                'status' => $doc->status,
                'status_label' => $doc->status->label(),
                'rejection_reason' => $doc->rejection_reason,
                'verified_at' => $doc->verified_at?->toIso8601String(),
                'created_at' => $doc->created_at?->toIso8601String(),
            ]),
        ];
    }
}
