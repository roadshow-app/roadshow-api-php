<?php

if (!function_exists('errorResponse')) {
    function errorResponse($message, $code) {
        logApiCall("error: " . $message);

        $message = (string) $message;
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}
