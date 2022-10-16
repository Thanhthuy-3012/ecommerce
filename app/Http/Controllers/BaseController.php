<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class BaseController extends Controller
{
    /**
     * log to file.
     *
     */
    public function log($file = null, $funct = null, $user_id = null, $data = null, $message = null)
    {
        $log = 'ERROR';
        if (!is_null($file)) $log = $log . PHP_EOL . '- FILE: ' . $file;
        if (!is_null($user_id)) $log = $log . PHP_EOL . '- USER_ID: ' . $user_id;
        if (!is_null($funct)) $log = $log . PHP_EOL . '- FUNCTION: ' . $funct;
        if (!is_null($data)) $log = $log . PHP_EOL . '- DATA: ' . json_encode($data, JSON_UNESCAPED_UNICODE);
        if (!is_null($message)) $log = $log . PHP_EOL . '- MESSAGE: ' . $message . PHP_EOL;
        Log::channel('log_error')->info($log);
    }

    /**
     * return success response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccessResponse($data = null, $message = null)
    {
        $response = [
            'success' => true,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error = null, $code = null)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if ($code) {
            return response()->json($response, $code);
        } else {
            return response()->json($response);
        }
    }

}
