<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class ApiController
{
    protected const PER_PAGE = 15;

    public function successJson(JsonResource $jsonResource, string $message = 'Success'): JsonResource
    {
        return $jsonResource->additional([
            ...$jsonResource->additional,
            'success' => true,
            'status' => 200,
            'message' => $message,
        ]);
    }

    public function errorJson(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => $status,
            'message' => $message,
        ], $status);
    }

    /**
     * Return a successful response.
     */
    public function successResponse($data = null, string $message = 'Request successful', int $code = 200, array $additional = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => $code,
            'message' => $message,
            'data' => $data,
            ...$additional,
        ], $code);
    }

    /**
     * Return an error response.
     */
    public function errorResponse(string $message, int $code = 400, array $additional = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => $code,
            'message' => $message,
            ...$additional,
        ], $code);
    }
}
