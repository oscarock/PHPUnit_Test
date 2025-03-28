<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success($data, int $status = 200): JsonResponse
    {
        return response()->json([
            "status" => $status,
            "data" => $data
        ], $status);
    }

    public static function error(Exception $e, int $status = 500): JsonResponse
    {
        return response()->json([
            "status" => $status,
            "details" => $e->getMessage()
        ], $status);
    }
}
