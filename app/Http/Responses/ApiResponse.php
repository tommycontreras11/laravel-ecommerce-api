<?php

namespace App\Http\Responses;

class ApiResponse 
{
    public static function success($message = 'Success', $statusCode = 200, $data = []) 
    {
        return response()->json([
            'message' => $message,
            'error' => false,
            'statusCode' => $statusCode,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message = 'Error', $statusCode, $data = []) 
    {
        return response()->json([
            'message' => $message,
            'error' => true,
            'statusCode' => $statusCode,
            'data' => $data
        ], $statusCode);
    }
}
