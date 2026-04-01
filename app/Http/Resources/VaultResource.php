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
            'currency_symbol' => $this->currency?->symbol(),
            'type' => $this->type,
            'type_label' => $this->type?->label(),
            'type_color' => $this->type?->color(),
            'status' => $this->status,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'maturity_date' => $this->maturity_date?->format('d/m/Y'), // 01/01/2025
            'is_locked' => $this->isLocked(),
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at?->format('d/m/Y'), // 01/01/2025
            'transactions' => VaultTransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
