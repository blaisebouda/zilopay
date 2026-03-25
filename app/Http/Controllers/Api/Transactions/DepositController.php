<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Transactions\StoreDepositRequest;
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
    public function store(StoreDepositRequest $request): JsonResponse
    {
        try {
            $deposit = $this->depositService->createDeposit(
                $request->user(),
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Deposit initiated successfully',
                'data' => [
                    'id' => $deposit->id,
                    'reference' => $deposit->reference,
                    'amount' => $deposit->amount,
                    'status' => $deposit->status->label(),
                    'external_reference' => $deposit->external_reference,
                    'created_at' => $deposit->created_at,
                ],
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Deposit creation failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create deposit',
            ], 500);
        }
    }

    /**
     * Handle deposit callback from gateway
     */
    public function callback(Request $request, string $reference): JsonResponse
    {
        try {
            $gatewayData = $request->all();

            $deposit = $this->depositService->confirmDeposit($reference, $gatewayData);

            return response()->json([
                'success' => true,
                'message' => 'Deposit confirmed',
                'data' => [
                    'reference' => $deposit->reference,
                    'status' => $deposit->status->label(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Deposit callback failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Cancel a pending deposit
     */
    public function cancel(Request $request, string $reference): JsonResponse
    {
        try {
            $deposit = $this->depositService->cancelDeposit(
                $reference,
                $request->input('reason', 'User cancelled')
            );

            return response()->json([
                'success' => true,
                'message' => 'Deposit cancelled successfully',
                'data' => [
                    'reference' => $deposit->reference,
                    'status' => $deposit->status->label(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
