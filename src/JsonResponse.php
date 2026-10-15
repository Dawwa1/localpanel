<?php

namespace Localpanel;

use React\Http\Message\Response;

class JsonResponse {
    public static function success($data = null, $message = 'Success', $statusCode = 200): Response {
        $responseData = [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
        return new Response($statusCode, ['Content-Type' => 'application/json'], json_encode($responseData));
    }

    public static function error($message = 'Error', $status = 400, string $code = 'REQ_FAILED'): Response {
        $responseData = [
            'status' => $status,
            'message' => $message,
            'code' => $code
        ];
        return new Response($statusCode, ['Content-Type' => 'application/json'], json_encode($responseData));
    }
}