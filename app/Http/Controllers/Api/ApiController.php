<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function setSuccess($data = null, $message = null, $statusCode = 200)
    {
        $response = [];

        if ($data) {
            $response["data"] = $data;
        }

        if ($message) {
            $response["message"] = $message;
        }

        return response()->json($response, $statusCode);
    }

    public function setError($message, $errors = null, $statusCode = 500)
    {
        $response = [
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
