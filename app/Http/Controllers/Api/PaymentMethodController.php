<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;



class PaymentMethodController extends ApiController
{

    public function index(): AnonymousResourceCollection
    {
        $paymentMethods = PaymentMethod::active()->get();


        return PaymentMethodResource::collection($paymentMethods);
    }

    public function show(PaymentMethod $paymentMethod): PaymentMethodResource
    {
        return new PaymentMethodResource($paymentMethod);
    }


    public function store(Request $request): PaymentMethodResource
    {
        $validated = $request->validate([
            'contry_id' => 'required|integer|exists:countries,id',
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'type' => 'required|string|in:mobile_money,card,bank_transfer,cash',
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'fee_percent' => 'required|numeric|min:0|max:100',
            'fee_fixed' => 'required|numeric|min:0',
        ]);

        $paymentMethod = PaymentMethod::create($validated);

        return new PaymentMethodResource($paymentMethod);
    }


    public function update(Request $request, PaymentMethod $paymentMethod): PaymentMethodResource
    {
        $validated = $request->validate([
            'contry_id' => 'sometimes|required|integer|exists:countries,id',
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'type' => 'sometimes|required|string|in:mobile_money,card,bank_transfer,cash',
            'code' => 'sometimes|required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'min_amount' => 'sometimes|required|numeric|min:0',
            'max_amount' => 'sometimes|required|numeric|min:0',
            'fee_percent' => 'sometimes|required|numeric|min:0|max:100',
            'fee_fixed' => 'sometimes|required|numeric|min:0',
        ]);

        $paymentMethod->update($validated);

        return new PaymentMethodResource($paymentMethod);
    }


    public function destroy(PaymentMethod $paymentMethod): \Illuminate\Http\JsonResponse
    {
        $paymentMethod->delete();

        return response()->json(null, 204);
    }
}
