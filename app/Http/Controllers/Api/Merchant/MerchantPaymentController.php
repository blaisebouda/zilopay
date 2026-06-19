<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\MerchantTransactionResource;
use App\Models\Merchant;
use App\Services\Merchant\MerchantPaymentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MerchantPaymentController extends ApiController
{
    public function __construct(
        private MerchantPaymentService $paymentService
    ) {}

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

            return $this->successResponse(
                new MerchantTransactionResource($transaction),
                'Payment retrieved successfully'
            );
        } catch (ModelNotFoundException $e) {
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
