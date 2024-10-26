<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('apiResponse')) {
    function apiResponse(
        $data = null,
        $message = null,
        $errors = null,
        $code = 200,
        $headers = []
    ): JsonResponse {

        $resource = [
            'data' => $data,
            'message' => $message,
            'status' => in_array($code, successCode()),
            'errors' => $errors,
        ];

        return response()->json($resource, $code, $headers);
    }
}

if (!function_exists('successCode')) {
    function successCode(): array
    {
        return [200, 201, 202];
    }
}
