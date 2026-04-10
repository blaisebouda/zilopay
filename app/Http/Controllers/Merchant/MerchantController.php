<?php

declare(strict_types=1);

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Merchant\StoreMerchantRequest;
use App\Http\Resources\MerchantResource;
use App\Services\Merchant\MerchantService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MerchantController extends ApiController
{
    public function __construct(
        private MerchantService $merchantService
    ) {}

    /**
     * Store a newly created merchant.
     */
    public function store(StoreMerchantRequest $request): JsonResponse
    {
        try {
            $merchant = $this->merchantService->create(
                $request->user(),
                $request->validated()
            );

            return $this->successResponse(
                new MerchantResource($merchant),
                'Le profil du marchand a été créé avec succès, veillez patienter pour la validation de votre demande.',
                201
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Failed to create merchant profile', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Une erreur est survenue lors de la création du profil du marchand.', 500);
        }
    }

    /**
     * Display the specified merchant.
     */
    public function show(Request $request): JsonResponse
    {
        try {
            /*
            @var \App\Models\Merchant $merchant
            */
            $merchant = $request->user()->merchant;

            if (!$merchant->isApproved()) {
                return $this->successResponse(
                    new MerchantResource($merchant),
                );
            }

            return $this->successResponse(
                new MerchantResource($merchant),
                'Merchant retrieved successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Marchant introuvable', 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve merchant', [
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Impossible de récupérer le marchand', 500);
        }
    }
}
