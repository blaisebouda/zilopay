<?php

declare(strict_types=1);

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\MerchantDashboardResource;
use App\Models\Merchant;
use App\Services\Merchant\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MerchantDashboardController extends ApiController
{
    public function __construct(
        private MerchantService $merchantService
    ) {}

    /**
     * Display dashboard statistics.
     */
    public function index(): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            $statistics = $this->merchantService->getStatistics($merchant);

            return $this->successResponse(
                new MerchantDashboardResource($statistics),
                'Dashboard statistics retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve dashboard statistics', [
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to retrieve dashboard statistics', 500);
        }
    }
}
