<?php

declare(strict_types=1);

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Merchant\StoreApiKeyRequest;
use App\Http\Resources\MerchantApiKeyResource;
use App\Models\Merchant;
use App\Models\MerchantApiKey;
use App\Services\Merchant\MerchantApiKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MerchantApiKeyController extends ApiController
{
    public function __construct(
        private MerchantApiKeyService $apiKeyService
    ) {}

    /**
     * Store a newly created API key.
     */
    public function store(StoreApiKeyRequest $request): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            $result = $this->apiKeyService->create($merchant, $request->validated());

            $apiKey = $result->api_key;
            $apiKey->plain_secret = $result->plain_secret;

            return $this->successResponse(
                new MerchantApiKeyResource($apiKey),
                'La clé API a été créée avec succès. Veuillez sauvegarder le secret, il ne sera pas affiché à nouveau.',
                201
            );
        } catch (\Exception $e) {
            Log::error('Failed to create API key', [
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Impossible de créer la clé API', 500);
        }
    }

    /**
     * Remove the specified API key.
     */
    public function destroy(MerchantApiKey $apiKey): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            if ($apiKey->merchant_id !== $merchant->id) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $this->apiKeyService->delete($apiKey);

            return $this->successResponse(
                null,
                'La clé API a été supprimée avec succès'
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete API key', [
                'api_key_id' => $apiKey->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Impossible de supprimer la clé API', 500);
        }
    }
}
