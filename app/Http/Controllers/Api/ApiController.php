<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class ApiController
{
    protected const PER_PAGE = 15;

    public function successResourceResponse(JsonResource $resource, string $message = 'Success'): JsonResource
    {
        return $resource->additional([
            ...$resource->additional,
            'success' => true,
            'status' => 200,
            'message' => $message,
        ]);
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
