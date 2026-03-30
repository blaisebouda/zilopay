<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Transactions\StoreTransferRequest;
use App\Http\Resources\TransactionResource;
use App\Services\Transactions\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class TransferController extends ApiController
{
    public function __construct(
        private TransferService $transferService
    ) {}

    /**
     * Create a new transfer
     */
    public function store(StoreTransferRequest $request): JsonResponse|JsonResource
    {
        try {
            $transfer = $this->transferService->create(
                $request->user(),
                $request->validated()
            );

            return $this->successResource(TransactionResource::make($transfer));
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Transfer creation failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
            // return $this->errorResponse('Failed to complete transfer', 500);
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
}
