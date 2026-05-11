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
            'logo_url' => $this->logoUrl(),
            'country_flag_url' => $this->countryFlagUrl(),
            'type' => $this->type,
            'country' => $this->country,
            'country_label' => $this->country?->label(),
            'country_phone_code' => $this->country?->phoneCode(),
            'code' => $this->code,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'fee_percent' => $this->fee_percent,
            'fee_fixed' => $this->fee_fixed,
        ];
    }
}
