<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrangeMoneyGateway
{
    private string $baseUrl;

    private string $clientId;

    private string $clientSecret;

    private string $merchantId;

    public function __construct()
    {
        $this->baseUrl = config('services.orange_money.base_url');
        $this->clientId = config('services.orange_money.client_id');
        $this->clientSecret = config('services.orange_money.client_secret');
        $this->merchantId = config('services.orange_money.merchant_id');
    }

    /**
     * Initiate a deposit via Orange Money
     */
    public function initiateDeposit(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/payment/v1/deposits", [
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'XOF',
                'reference' => $data['reference'],
                'phone_number' => $data['phone_number'],
                'description' => $data['description'] ?? 'BaraPay Deposit',
                'merchant_id' => $this->merchantId,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'external_reference' => $response->json('transaction_id'),
                    'status' => 'pending',
                    'gateway_data' => $response->json(),
                ];
            }

            Log::error('Orange Money deposit initiation failed', [
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message', 'Failed to initiate deposit'),
            ];
        } catch (\Exception $e) {
            Log::error('Orange Money deposit exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gateway error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a deposit status
     */
    public function verifyDeposit(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ])->get("{$this->baseUrl}/payment/v1/deposits/{$reference}");

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['status'] ?? 'unknown';

                return [
                    'success' => in_array($status, ['SUCCESS', 'COMPLETED']),
                    'status' => $status,
                    'amount' => $data['amount'] ?? null,
                    'gateway_data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to verify deposit',
            ];
        } catch (\Exception $e) {
            Log::error('Orange Money verification exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Verification error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel a pending deposit
     */
    public function cancelDeposit(?string $externalReference): array
    {
        if (! $externalReference) {
            return ['success' => true, 'message' => 'No external reference to cancel'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ])->post("{$this->baseUrl}/payment/v1/deposits/{$externalReference}/cancel");

            return [
                'success' => $response->successful(),
                'message' => $response->json('message', 'Cancel request processed'),
            ];
        } catch (\Exception $e) {
            Log::error('Orange Money cancel exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Cancel error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Initiate a withdrawal/payout
     */
    public function initiateWithdrawal(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/payment/v1/payouts", [
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'XOF',
                'reference' => $data['reference'],
                'phone_number' => $data['phone_number'],
                'description' => $data['description'] ?? 'BaraPay Withdrawal',
                'merchant_id' => $this->merchantId,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'external_reference' => $response->json('transaction_id'),
                    'status' => 'pending',
                    'gateway_data' => $response->json(),
                ];
            }

            Log::error('Orange Money withdrawal initiation failed', [
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message', 'Failed to initiate withdrawal'),
            ];
        } catch (\Exception $e) {
            Log::error('Orange Money withdrawal exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gateway error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a withdrawal/payout status
     */
    public function verifyWithdrawal(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ])->get("{$this->baseUrl}/payment/v1/payouts/{$reference}");

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['status'] ?? 'unknown';

                return [
                    'success' => in_array($status, ['SUCCESS', 'COMPLETED']),
                    'status' => $status,
                    'amount' => $data['amount'] ?? null,
                    'gateway_data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to verify withdrawal',
            ];
        } catch (\Exception $e) {
            Log::error('Orange Money withdrawal verification exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Verification error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get access token from Orange Money API
     */
    private function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->post("{$this->baseUrl}/oauth/token", [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            throw new \Exception('Failed to obtain access token');
        }

        return $response->json('access_token');
    }
}
