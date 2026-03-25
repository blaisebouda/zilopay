<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @SWG\Definition(
 *     definition="PaymentMethodResource",
 *     title="Payment Method Resource",
 *     description="Payment method resource representation",
 *
 *     @SWG\Property(
 *         property="id",
 *         type="integer",
 *         description="Payment method ID",
 *         example=1
 *     ),
 *     @SWG\Property(
 *         property="contry_id",
 *         type="integer",
 *         description="Country ID",
 *         example=1
 *     ),
 *     @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="Payment method name",
 *         example="Orange Money"
 *     ),
 *     @SWG\Property(
 *         property="logo",
 *         type="string",
 *         description="Payment method logo URL",
 *         example="https://example.com/logo.png"
 *     ),
 *     @SWG\Property(
 *         property="type",
 *         type="string",
 *         description="Payment method type",
 *         enum={"mobile_money", "card", "bank_transfer", "cash"},
 *         example="mobile_money"
 *     ),
 *     @SWG\Property(
 *         property="code",
 *         type="string",
 *         description="Payment method code",
 *         example="OM"
 *     ),
 *     @SWG\Property(
 *         property="min_amount",
 *         type="number",
 *         format="decimal",
 *         description="Minimum transaction amount",
 *         example=100.00
 *     ),
 *     @SWG\Property(
 *         property="max_amount",
 *         type="number",
 *         format="decimal",
 *         description="Maximum transaction amount",
 *         example=1000000.00
 *     ),
 *     @SWG\Property(
 *         property="fee_percent",
 *         type="number",
 *         format="decimal",
 *         description="Transaction fee percentage",
 *         example=1.50
 *     ),
 *     @SWG\Property(
 *         property="fee_fixed",
 *         type="number",
 *         format="decimal",
 *         description="Fixed transaction fee",
 *         example=50.00
 *     ),
 *     @SWG\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp",
 *         example="2024-01-01T12:00:00.000000Z"
 *     ),
 *     @SWG\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update timestamp",
 *         example="2024-01-01T12:00:00.000000Z"
 *     )
 * )
 */
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
            'type' => $this->type,
            'code' => $this->code,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'fee_percent' => $this->fee_percent,
            'fee_fixed' => $this->fee_fixed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

/**
 * @SWG\Definition(
 *     definition="StorePaymentMethodRequest",
 *     title="Store Payment Method Request",
 *     description="Request body for storing a new payment method",
 *
 *     @SWG\Property(
 *         property="contry_id",
 *         type="integer",
 *         description="Country ID",
 *         example=1
 *     ),
 *     @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="Payment method name",
 *         example="Orange Money"
 *     ),
 *     @SWG\Property(
 *         property="logo",
 *         type="string",
 *         description="Payment method logo URL",
 *         example="https://example.com/logo.png"
 *     ),
 *     @SWG\Property(
 *         property="type",
 *         type="string",
 *         description="Payment method type",
 *         enum={"mobile_money", "card", "bank_transfer", "cash"},
 *         example="mobile_money"
 *     ),
 *     @SWG\Property(
 *         property="code",
 *         type="string",
 *         description="Payment method code",
 *         example="OM"
 *     ),
 *     @SWG\Property(
 *         property="min_amount",
 *         type="number",
 *         format="decimal",
 *         description="Minimum transaction amount",
 *         example=100.00
 *     ),
 *     @SWG\Property(
 *         property="max_amount",
 *         type="number",
 *         format="decimal",
 *         description="Maximum transaction amount",
 *         example=1000000.00
 *     ),
 *     @SWG\Property(
 *         property="fee_percent",
 *         type="number",
 *         format="decimal",
 *         description="Transaction fee percentage",
 *         example=1.50
 *     ),
 *     @SWG\Property(
 *         property="fee_fixed",
 *         type="number",
 *         format="decimal",
 *         description="Fixed transaction fee",
 *         example=50.00
 *     )
 * )
 */

/**
 * @SWG\Definition(
 *     definition="UpdatePaymentMethodRequest",
 *     title="Update Payment Method Request",
 *     description="Request body for updating an existing payment method",
 *
 *     @SWG\Property(
 *         property="contry_id",
 *         type="integer",
 *         description="Country ID",
 *         example=1
 *     ),
 *     @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="Payment method name",
 *         example="Orange Money"
 *     ),
 *     @SWG\Property(
 *         property="logo",
 *         type="string",
 *         description="Payment method logo URL",
 *         example="https://example.com/logo.png"
 *     ),
 *     @SWG\Property(
 *         property="type",
 *         type="string",
 *         description="Payment method type",
 *         enum={"mobile_money", "card", "bank_transfer", "cash"},
 *         example="mobile_money"
 *     ),
 *     @SWG\Property(
 *         property="code",
 *         type="string",
 *         description="Payment method code",
 *         example="OM"
 *     ),
 *     @SWG\Property(
 *         property="min_amount",
 *         type="number",
 *         format="decimal",
 *         description="Minimum transaction amount",
 *         example=100.00
 *     ),
 *     @SWG\Property(
 *         property="max_amount",
 *         type="number",
 *         format="decimal",
 *         description="Maximum transaction amount",
 *         example=1000000.00
 *     ),
 *     @SWG\Property(
 *         property="fee_percent",
 *         type="number",
 *         format="decimal",
 *         description="Transaction fee percentage",
 *         example=1.50
 *     ),
 *     @SWG\Property(
 *         property="fee_fixed",
 *         type="number",
 *         format="decimal",
 *         description="Fixed transaction fee",
 *         example=50.00
 *     )
 * )
 */
