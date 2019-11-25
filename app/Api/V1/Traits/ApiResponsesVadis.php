<?php
namespace App\Api\V1\Traits;

use Illuminate\Http\Response;

trait ApiResponsesVadis {
    public function apiResponses($success, $code, $message, $data)
    {
        $response = [
            "success"  => $success,
            "code"     => $code,
            "message" => $message,
            "data"     => $data
        ];

        return response()->json($response, $code);
    }
}
