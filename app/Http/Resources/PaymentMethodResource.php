<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
            'name' => $this->name,
            'logo' => $this->logo,
            'type' => $this->type->label(),
            'code' => $this->code,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'fee_percent' => $this->fee_percent,
            'fee_fixed' => $this->fee_fixed,
        ];
    }
}
