<?php

namespace App\Http\Responses;

use Exception;
use Illuminate\Http\JsonResponse;

class Response
{
    public static function Success($data = [] , $message , $status = 200):JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message
        ] , $status);
    }

    public static function Error($data , $status = 500):JsonResponse
    {
        return response()->json([
            'status' => false,
            'data' => $data,
            // 'message' => [
                // 'Error_details' => $exception->getMessage(),
                // 'File' => $exception->getFile(),
                // 'Line' => $exception->getLine(),
            // ]
        ] , $status);
    }

    public static function validation($data , $message , $code = 422):JsonResponse
    {
        return response()->json([
            'status' => null,
            'data' => $data,
            'message' => $message
        ]);
    }

}
