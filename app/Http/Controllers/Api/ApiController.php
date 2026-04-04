<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

abstract class ApiController
{
    protected const PER_PAGE = 15;

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

    public function debugResponse($data = null, string $message = 'Debug'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
