<?php

namespace App;

use Illuminate\Http\JsonResponse;

class ResponseFormater
{
    public static function responseMessage($message, $code): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }

    public static function responseData($data, $code): JsonResponse
    {
        return response()->json($data, $code);
    }
}
