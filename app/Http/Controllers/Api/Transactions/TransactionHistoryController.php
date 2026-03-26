<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Api\ApiController;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionHistoryController extends ApiController
{
    /**
     * Get transaction history for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::forUser($request->user()->id)
            ->with(['currency', 'paymentMethod', 'deposit', 'withdrawal', 'transfer']);

        // Filter by type
        if ($request->has('type')) {
            $query->ofType($request->input('type'));
        }

        // Filter by status
        if ($request->has('status')) {
            $query->withStatus($request->input('status'));
        }

        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->forDateRange($request->input('from_date'), $request->input('to_date'));
        }

        // Pagination
        $perPage = $request->input('per_page', 20);
        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Get recent transactions
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        $transactions = Transaction::forUser($request->user()->id)
            ->recent($limit)
            ->with(['currency', 'paymentMethod'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Get transaction summary/stats
     */
    public function stats(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $deposits = Transaction::forUser($userId)
            ->deposits()
            ->completed()
            ->sum('amount');

        $withdrawals = Transaction::forUser($userId)
            ->withdrawals()
            ->completed()
            ->sum('amount');

        $transfers = Transaction::forUser($userId)
            ->transfers()
            ->completed()
            ->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_deposits' => $deposits,
                'total_withdrawals' => abs($withdrawals),
                'total_transfers' => abs($transfers),
                'net_flow' => $deposits - abs($withdrawals) - abs($transfers),
            ],
        ]);
    }
}
