<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Transactions\StoreWithdrawalRequest;
use App\Services\Transactions\WithdrawalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends ApiController
{
    public function __construct(
        private WithdrawalService $withdrawalService
    ) {}

    /**
     * Create a new withdrawal
     */
    public function store(StoreWithdrawalRequest $request): JsonResponse
    {
        try {
            $withdrawal = $this->withdrawalService->createWithdrawal(
                $request->user(),
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal initiated successfully',
                'data' => [
                    'id' => $withdrawal->id,
                    'reference' => $withdrawal->reference,
                    'amount' => $withdrawal->amount,
                    'fees' => $withdrawal->fees,
                    'status' => $withdrawal->status->label(),
                    'external_reference' => $withdrawal->external_reference,
                    'created_at' => $withdrawal->created_at,
                ],
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Withdrawal creation failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create withdrawal',
            ], 500);
        }
    }

    /**
     * Handle withdrawal callback from gateway
     */
    public function callback(Request $request, string $reference): JsonResponse
    {
        try {
            $gatewayData = $request->all();

            $withdrawal = $this->withdrawalService->confirmWithdrawal($reference, $gatewayData);

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal completed',
                'data' => [
                    'reference' => $withdrawal->reference,
                    'status' => $withdrawal->status->label(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Withdrawal callback failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
