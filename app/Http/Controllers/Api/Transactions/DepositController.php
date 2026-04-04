<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Transactions\StoreDepositRequest;
use App\Http\Resources\TransactionResource;
use App\Services\Transactions\DepositService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepositController extends ApiController
{
    public function __construct(
        private DepositService $depositService
    ) {}

    /**
     * Create a new deposit
     */
    public function init(StoreDepositRequest $request): JsonResponse
    {
        try {
            $deposit = $this->depositService->create(
                $request->user(),
                $request->validated()
            );

            return $this->successResponse(TransactionResource::make($deposit), 'Deposit created successfully');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Deposit creation failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to create deposit', 500);
        }
    }

    /**
     * Handle deposit callback from gateway
     */
    public function confirm(Request $request, string $reference): JsonResponse
    {
        try {
            $gatewayData = $request->all();

            $deposit = $this->depositService->confirm($reference, $gatewayData);

            return $this->successResponse([
                'reference' => $deposit->reference,
                'status' => $deposit->status->label(),
            ], 'Deposit confirmed');
        } catch (\Exception $e) {
            Log::error('Deposit callback failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Cancel a pending deposit
     */
    public function cancel(Request $request, string $reference): JsonResponse
    {
        try {
            $deposit = $this->depositService->cancel(
                $reference,
                $request->input('reason', 'User cancelled')
            );

            return $this->successResponse([
                'reference' => $deposit->reference,
                'status' => $deposit->status->label(),
            ], 'Deposit cancelled successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
