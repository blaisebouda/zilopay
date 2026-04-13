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
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


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
                    new MerchantResource($merchant->load('documents')),
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

    #[Authorize('view', 'merchant')]
    public function downloadDocument(Request $request, string $path): StreamedResponse|JsonResponse
    {
        try {

            if (!Storage::disk('local')->exists($path)) {
                return $this->errorResponse('Fichier non trouvé', 404);
            }

            return Storage::disk('local')->download($path, basename($path));
        } catch (\Exception $e) {
            Log::error('Failed to download merchant document', [
                'user_id' => $request->user()->id,
                'document_path' => $path,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Impossible de télécharger le document', 500);
        }
    }
}
