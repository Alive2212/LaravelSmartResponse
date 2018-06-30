<?php

use Alive2212\LaravelSmartResponse\ResponseModel;

/**
 * Created by PhpStorm.
 * User: alive
 * Date: 8/23/17
 * Time: 6:46 AM
 */

class SmartResponse
{
    /**
     * @param ResponseModel $response
     * @return \Illuminate\Http\JsonResponse
     */
    public static function response(ResponseModel $response)
    {
        $header = array(
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        if (!is_null($response->getError())) {
            return response()->json([
                'status' => $response->getStatus(),
                'data' => $response->getData()->count() ? $response->getData() : null,
                'message' => $response->getMessage(),
                'error_code' => $response->getError(),
            ], $response->getstatusCode(), $header, JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'status' => $response->getStatus(),
                'data' => $response->getData()->count() ? $response->getData() : null,
                'message' => $response->getMessage(),
            ], $response->getStatusCode(), $header, JSON_UNESCAPED_UNICODE);
        }
    }
}

