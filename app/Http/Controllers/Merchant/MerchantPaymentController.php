<?php

declare(strict_types=1);

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Merchant\InitiatePaymentRequest;
use App\Http\Resources\MerchantTransactionResource;
use App\Models\Merchant;
use App\Services\Merchant\MerchantPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MerchantPaymentController extends ApiController
{
    public function __construct(
        private MerchantPaymentService $paymentService
    ) {}

    /**
     * Initiate a new payment.
     */
    public function initiate(InitiatePaymentRequest $request): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            $transaction = $this->paymentService->initiate($merchant, $request->validated());

            $feeCalculation = $this->paymentService->calculateFees(
                (float) $transaction->amount,
                $merchant
            );

            return $this->successResponse(
                [
                    'transaction' => new MerchantTransactionResource($transaction),
                    'fees' => $feeCalculation,
                    'payment_url' => url("/merchant/payments/{$transaction->uuid}"),
                ],
                'Payment initiated successfully',
                201
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Failed to initiate payment', [
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to initiate payment', 500);
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(string $uuid): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            $transaction = $this->paymentService->getByUuid($uuid);

            if ($transaction->merchant_id !== $merchant->id) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $feeCalculation = $this->paymentService->calculateFees(
                (float) $transaction->amount,
                $merchant
            );

            return $this->successResponse(
                [
                    'transaction' => new MerchantTransactionResource($transaction),
                    'fees' => $feeCalculation,
                ],
                'Payment retrieved successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Payment not found', 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve payment', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to retrieve payment', 500);
        }
    }
}
