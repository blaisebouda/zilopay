<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type->label(),
            'target' => $this->target(),
            'reference' => $this->uuid,
            'amount' => $this->formatAmount(),
            'status' => $this->status->label(),
            'status_color' => $this->status->color(),
            'created_at' => $this->created_at,
            'date' => $this->created_at->format('m d, H:i'), //	Oct 23, 09:45
            'operator' => $this->operator(),
            'is_deposit' => $this->isDeposit(),
            'is_withdrawal' => $this->isWithdrawal(),
            'is_transfer' => $this->isTransfer(),
        ];
    }
}
