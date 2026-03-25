<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Transactions\StoreTransferRequest;
use App\Services\Transactions\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransferController extends ApiController
{
    public function __construct(
        private TransferService $transferService
    ) {}

    /**
     * Create a new transfer
     */
    public function store(StoreTransferRequest $request): JsonResponse
    {
        try {
            $transfer = $this->transferService->createTransfer(
                $request->user(),
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Transfer completed successfully',
                'data' => [
                    'id' => $transfer->id,
                    'amount' => $transfer->amount,
                    'fee' => $transfer->fee,
                    'currency' => $transfer->currency->code,
                    'sender_wallet_id' => $transfer->sender_wallet_id,
                    'receiver_wallet_id' => $transfer->receiver_wallet_id,
                    'note' => $transfer->note,
                    'created_at' => $transfer->created_at,
                ],
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Transfer creation failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete transfer',
            ], 500);
        }
    }

    /**
     * Get transfer history for authenticated user
     */
    public function history(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'from_date', 'to_date', 'min_amount', 'max_amount']);

        $transfers = $this->transferService->getTransferHistory($request->user(), $filters);

        return response()->json([
            'success' => true,
            'data' => $transfers,
        ]);
    }

    /**
     * Get sent transfers
     */
    public function sent(Request $request): JsonResponse
    {
        $transfers = $this->transferService->getSentTransfers($request->user());

        return response()->json([
            'success' => true,
            'data' => $transfers,
        ]);
    }

    /**
     * Get received transfers
     */
    public function received(Request $request): JsonResponse
    {
        $transfers = $this->transferService->getReceivedTransfers($request->user());

        return response()->json([
            'success' => true,
            'data' => $transfers,
        ]);
    }
}
