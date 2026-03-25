<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentMethodController extends ApiController
{
    /**
     * @SWG\Get(
     *      path="/payment-methods",
     *      operationId="getPaymentMethodsList",
     *      tags={"PaymentMethods"},
     *      summary="Get list of payment methods",
     *      description="Returns list of payment methods",
     *
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @SWG\Schema(
     *              type="array",
     *
     *              @SWG\Items(
     *                  ref="#/definitions/PaymentMethodResource"
     *              )
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @SWG\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $paymentMethods = PaymentMethod::query()
            ->orderBy('name')
            ->paginate(15);

        return PaymentMethodResource::collection($paymentMethods);
    }

    /**
     * @SWG\Get(
     *      path="/payment-methods/{id}",
     *      operationId="getPaymentMethodById",
     *      tags={"PaymentMethods"},
     *      summary="Get payment method information",
     *      description="Returns payment method data",
     *
     *      @SWG\Parameter(
     *          name="id",
     *          description="Payment method id",
     *          required=true,
     *          in="path",
     *
     *          @SWG\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @SWG\Schema(
     *              ref="#/definitions/PaymentMethodResource"
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @SWG\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @SWG\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function show(PaymentMethod $paymentMethod): PaymentMethodResource
    {
        return new PaymentMethodResource($paymentMethod);
    }

    /**
     * @SWG\Post(
     *      path="/payment-methods",
     *      operationId="storePaymentMethod",
     *      tags={"PaymentMethods"},
     *      summary="Store new payment method",
     *      description="Returns payment method data",
     *
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *
     *          @SWG\Schema(
     *              ref="#/definitions/StorePaymentMethodRequest"
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=201,
     *          description="Successful operation",
     *
     *          @SWG\Schema(
     *              ref="#/definitions/PaymentMethodResource"
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @SWG\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
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

    /**
     * @SWG\Put(
     *      path="/payment-methods/{id}",
     *      operationId="updatePaymentMethod",
     *      tags={"PaymentMethods"},
     *      summary="Update existing payment method",
     *      description="Returns updated payment method data",
     *
     *      @SWG\Parameter(
     *          name="id",
     *          description="Payment method id",
     *          required=true,
     *          in="path",
     *
     *          @SWG\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *
     *          @SWG\Schema(
     *              ref="#/definitions/UpdatePaymentMethodRequest"
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @SWG\Schema(
     *              ref="#/definitions/PaymentMethodResource"
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @SWG\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @SWG\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(Request $request, PaymentMethod $paymentMethod): PaymentMethodResource
    {
        $validated = $request->validate([
            'contry_id' => 'sometimes|required|integer|exists:countries,id',
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'type' => 'sometimes|required|string|in:mobile_money,card,bank_transfer,cash',
            'code' => 'sometimes|required|string|max:50|unique:payment_methods,code,'.$paymentMethod->id,
            'min_amount' => 'sometimes|required|numeric|min:0',
            'max_amount' => 'sometimes|required|numeric|min:0',
            'fee_percent' => 'sometimes|required|numeric|min:0|max:100',
            'fee_fixed' => 'sometimes|required|numeric|min:0',
        ]);

        $paymentMethod->update($validated);

        return new PaymentMethodResource($paymentMethod);
    }

    /**
     * @SWG\Delete(
     *      path="/payment-methods/{id}",
     *      operationId="deletePaymentMethod",
     *      tags={"PaymentMethods"},
     *      summary="Delete existing payment method",
     *      description="Deletes a payment method record",
     *
     *      @SWG\Parameter(
     *          name="id",
     *          description="Payment method id",
     *          required=true,
     *          in="path",
     *
     *          @SWG\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *
     *      @SWG\Response(
     *          response=204,
     *          description="Successful operation",
     *
     *          @SWG\Schema()
     *      ),
     *
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @SWG\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @SWG\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy(PaymentMethod $paymentMethod): JsonResponse
    {
        $paymentMethod->delete();

        return response()->json(null, 204);
    }
}
