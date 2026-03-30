<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VaultTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'amount' => $this->amount,
            'type' => $this->type,
            'type_label' => $this->type?->label(),
            'type_color' => $this->type?->color(),
            'description' => $this->description,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
