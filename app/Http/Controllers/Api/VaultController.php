<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Vault\DepositRequest;
use App\Http\Requests\Vault\StoreVaultRequest;
use App\Http\Requests\Vault\WithdrawRequest;
use App\Http\Resources\VaultResource;
use App\Http\Resources\VaultTransactionResource;
use App\Models\Vault;
use App\Services\Vault\VaultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class VaultController extends ApiController
{
    public function __construct(
        private VaultService $vaultService
    ) {}

    /**
     * List user vaults
     */
    public function index(): JsonResponse
    {
        try {
            $vaults = $this->vaultService->getUserVaults(request()->user());

            return $this->successResponse(
                VaultResource::collection($vaults),
                'Coffres-forts récupérés avec succès'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve vaults', [
                'user_id' => request()->user()->id,
                'error' => $e->getMessage(),
            ]);

            // return $this->debugResponse($e->getMessage(), 'Échec de la récupération des coffres-forts');
            return $this->errorResponse('Échec de la récupération des coffres-forts', 500);
        }
    }

    /**
     * Create a new vault
     */
    public function store(StoreVaultRequest $request): JsonResponse
    {
        try {
            $vault = $this->vaultService->create(
                $request->user(),
                $request->validated()
            );

            return $this->successResponse(
                new VaultResource($vault->refresh()),
                'Coffre-fort créé avec succès'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create vault', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Échec de la création du coffre-fort', 500);
        }
    }

    /**
     * Show vault details
     */
    public function show(Vault $vault): JsonResponse
    {
        try {

            $vault = $this->vaultService->getVaultWithTransactions($vault);

            return $this->successResponse(
                new VaultResource($vault->refresh()),
                'Coffre-fort récupéré avec succès'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve vault', [
                'vault_id' => $vault->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Échec de la récupération du coffre-fort', 500);
        }
    }

    /**
     * Deposit money into vault
     */
    public function deposit(DepositRequest $request, Vault $vault): JsonResponse
    {
        try {
            $transaction = $this->vaultService->deposit(
                $vault,
                $request->input('wallet_id'),
                $request->input('amount'),
                $request->input('description')
            );

            return $this->successResponse(
                new VaultTransactionResource($transaction->refresh()),
                'Dépôt effectué avec succès'
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Failed to deposit to vault', [
                'vault_id' => $vault->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            //return $this->debugResponse($e->getMessage());

            return $this->errorResponse('Échec du dépôt', 500);
        }
    }

    /**
     * Withdraw money from vault
     */
    public function withdraw(WithdrawRequest $request, Vault $vault): JsonResponse
    {
        try {

            $transaction = $this->vaultService->withdraw(
                $vault,
                $request->input('wallet_id'),
                $request->input('amount'),
                $request->input('description')
            );

            return $this->successResponse(
                new VaultTransactionResource($transaction->refresh()),
                'Retrait effectué avec succès'
            );
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Failed to withdraw from vault', [
                'vault_id' => $vault->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            // return $this->debugResponse($e->getMessage());

            return $this->errorResponse('Échec du retrait', 500);
        }
    }

    /**
     * Toggle vault lock status
     */
    public function toggle(Vault $vault): JsonResponse
    {
        try {


            $vault = $this->vaultService->toggleLock($vault);

            $message = $vault->isLocked()
                ? 'Coffre-fort verrouillé avec succès'
                : 'Coffre-fort déverrouillé avec succès';

            return $this->successResponse(
                new VaultResource($vault),
                $message
            );
        } catch (\Exception $e) {
            Log::error('Failed to toggle vault lock', [
                'vault_id' => $vault->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Échec du changement de statut', 500);
        }
    }
}
