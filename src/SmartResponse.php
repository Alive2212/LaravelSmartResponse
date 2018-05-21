<?php
/**
 * Created by PhpStorm.
 * User: alive
 * Date: 8/23/17
 * Time: 6:46 AM
 */

namespace Alive2212\LaravelSmartResponse;

use App\Resources\Event\EventHandler;
use App\Resources\Model\ResponseModel;

class SmartResponse
{
    /**
     * @param string $message
     * @param bool $status
     * @param int $statusCode
     * @param array $data
     * @param int $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function json($message = "nothing",
                                $status = true,
                                $statusCode = 200,
                                $data = [],
                                $errorCode = 0)
    {
        $header = array(
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        if ($errorCode == 0) {
            return response()->json([
                'status' => $status,
                'data' => $data,
                'message' => $message,
            ], $statusCode, $header, JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'status' => $status,
                'data' => $data,
                'message' => $message,
                'error_code' => $errorCode,
            ], $statusCode, $header, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param MessageHelper $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function message(MessageHelper $message)
    {
        (new EventHandler($message));
        $header = array(
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        if ($message->getErrorCode() == -1) {
            return response()->json([
                'status' => $message->getStatus(),
                'data' => $message->getData(),
                'message' => $message->getContent(),
            ], $message->getStatusCode(), $header, JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'status' => $message->getStatus(),
                'data' => $message->getData(),
                'message' => $message->getContent(),
                'error_code' => $message->getErrorCode(),
            ], $message->getstatusCode(), $header, JSON_UNESCAPED_UNICODE);
        }
    }

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
        if (is_null($response->getError())) {
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

