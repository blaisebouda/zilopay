<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'merchant' => new MerchantResource($this['merchant']),
            'statistics' => [
                'total_revenue' => (float) $this['total_revenue'],
                'pending_payments_count' => $this['pending_payments_count'],
                'payment_links_count' => $this['payment_links_count'],
                'active_payment_links_count' => $this['active_payment_links_count'],
            ],
            'recent_payments' => MerchantTransactionResource::collection($this['recent_payments']),
        ];
    }
}
