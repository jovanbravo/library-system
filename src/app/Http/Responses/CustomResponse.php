<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class CustomResponse
{
    /**
     * Success Request Custom Response
     *
     * @param array|string $message
     * @param array|object|null $data
     * @param int $status
     * @return JsonResponse
     */
    public static function successRequest(array|string $message = [], array|object|null $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            'type' => 'general_success'
        ]);
    }

    /**
     * Bad Request Custom Response
     *
     * @param string|object $exception
     * @param int $status
     * @param string $error
     * @return JsonResponse
     */
    public static function badRequest(string|object $exception, int $status = 400, string $error = "Bad Request"): JsonResponse
    {
        return Response::json([
            "timestamp" => date('Y-m-d\TH:i:s.vP'),
            "status" => $check['code'] ?? $status,
            "error" => $error,
            "message" => is_string($exception) ? $exception : $exception->getMessage(),
            "path" => request()->path()
        ], $status);
    }
}
