<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    public static function responseApi($data = [], $messageKey = null, $status = 200, $error = null): JsonResponse
    {
        $locale = app()->getLocale();
        $message = $messageKey ? __("messages.$messageKey", [], $locale) : null;
        $response = ['success' => $status < 400];
        if ($message) $response['message'] = $message;
        if ($error) $response['error'] = $error;
        if (!empty($data)) $response['data'] = $data;
        return response()->json($response, $status);
    }
} 