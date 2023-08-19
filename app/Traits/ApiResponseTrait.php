<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($message = '', $data = [], $statusCode = 200)
    {
        $response = [
            'status' => 'success',
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }

    public function errorResponse($message = '', $data = [],  $statusCode = 400)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }
}