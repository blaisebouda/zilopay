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
            'type' => $this->transaction_type->label(),
            'target' => $this->target,
            'reference' => $this->uuid,
            'amount' => $this->formatAmount(),
            'status' => $this->status->label(),
            'status_color' => $this->status->color(),
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'operator' => $this->paymentMethod?->name ?? 'N/A',
            'is_deposit' => $this->isDeposit(),
            'is_withdrawal' => $this->isWithdrawal(),
            'is_transfer' => $this->isTransfer(),
        ];
    }
}
