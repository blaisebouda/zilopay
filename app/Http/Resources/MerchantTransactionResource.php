<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantTransactionResource extends JsonResource
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
            'amount' =>  $this->net_amount,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency->symbol(),
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'created_at' => $this->created_at?->toIso8601String(),
            'date' => $this->created_at?->diffForHumans(),
        ];
    }
}
