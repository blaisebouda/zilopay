<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VaultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'type' => $this->type,
            'type_label' => $this->type?->label(),
            'type_color' => $this->type?->color(),
            'status' => $this->status,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'maturity_date' => $this->maturity_date?->format('Y-m-d H:i:s'),
            'is_locked' => $this->isLocked(),
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'transactions' => VaultTransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
