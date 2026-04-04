<?php

declare(strict_types=1);

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Merchant\ProcessPaymentLinkRequest;
use App\Http\Requests\Merchant\StorePaymentLinkRequest;
use App\Http\Requests\Merchant\UpdatePaymentLinkRequest;
use App\Http\Resources\MerchantTransactionResource;
use App\Http\Resources\PaymentLinkResource;
use App\Models\Merchant;
use App\Models\PaymentLinks;
use App\Services\Merchant\MerchantPaymentService;
use App\Services\Merchant\PaymentLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentLinkController extends ApiController
{
    public function __construct(
        private PaymentLinkService $paymentLinkService,
        private MerchantPaymentService $paymentService
    ) {}

    /**
     * Display a listing of payment links.
     */
    public function index(): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            $paymentLinks = $this->paymentLinkService->getAllForMerchant($merchant);

            return $this->successResponse(
                PaymentLinkResource::collection($paymentLinks),
                'Le lien de paiement a été récupéré avec succès'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve payment links', [
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Une erreur est survenue lors de la récupération des liens de paiement', 500);
        }
    }

    /**
     * Store a newly created payment link.
     */
    public function store(StorePaymentLinkRequest $request): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            $paymentLink = $this->paymentLinkService->create($merchant, $request->validated());

            return $this->successResponse(
                new PaymentLinkResource($paymentLink),
                'Le lien de paiement a été créé avec succès',
                201
            );
        } catch (\Exception $e) {
            Log::error('Failed to create payment link', [
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to create payment link', 500);
        }
    }

    /**
     * Display the specified payment link (public).
     */
    public function show(string $uuid): JsonResponse
    {
        try {
            $paymentLink = $this->paymentLinkService->getByUuid($uuid);

            $validation = $this->paymentLinkService->validateForPayment($paymentLink);

            return $this->successResponse(
                [
                    'payment_link' => new PaymentLinkResource($paymentLink),
                    'is_valid' => $validation['valid'],
                    'validation_message' => $validation['valid'] ? null : $validation['message'],
                ],
                'Le lien de paiement a été récupéré avec succès'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Le lien de paiement n\'existe pas', 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve payment link', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to retrieve payment link', 500);
        }
    }

    /**
     * Update the specified payment link.
     */
    public function update(UpdatePaymentLinkRequest $request, PaymentLinks $paymentLink): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            if ($paymentLink->merchant_id !== $merchant->id) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $paymentLink = $this->paymentLinkService->update($paymentLink, $request->validated());

            return $this->successResponse(
                new PaymentLinkResource($paymentLink),
                'Le lien de paiement a été mis à jour avec succès'
            );
        } catch (\Exception $e) {
            Log::error('Failed to update payment link', [
                'payment_link_id' => $paymentLink->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to update payment link', 500);
        }
    }

    /**
     * Remove the specified payment link.
     */
    public function destroy(PaymentLinks $paymentLink): JsonResponse
    {
        try {
            /** @var Merchant $merchant */
            $merchant = request()->attributes->get('merchant');

            if ($paymentLink->merchant_id !== $merchant->id) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $this->paymentLinkService->delete($paymentLink);

            return $this->successResponse(
                null,
                'Le lien de paiement a été supprimé avec succès'
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete payment link', [
                'payment_link_id' => $paymentLink->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to delete payment link', 500);
        }
    }

    /**
     * Process payment via payment link (public).
     */
    public function process(ProcessPaymentLinkRequest $request, string $uuid): JsonResponse
    {
        try {
            $paymentLink = $this->paymentLinkService->getByUuid($uuid);

            $transaction = $this->paymentService->processViaLink($paymentLink, $request->validated());

            return $this->successResponse(
                new MerchantTransactionResource($transaction),
                'Paiement initié avec succès',
                201
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Le lien de paiement n\'existe pas', 404);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Failed to process payment link', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Échec du traitement du paiement', 500);
        }
    }
}
