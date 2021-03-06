<?php

/**
 * Created by PhpStorm.
 * User: Alive...
 * Date: 8/23/17
 * Time: 6:46 AM
 */

namespace Alive2212\LaravelSmartResponse;

use Illuminate\Support\Arr;

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
        $responseParams = [];
        if ($response->getData()->count()) {
            $responseParams = Arr::add($responseParams, 'results', $response->getData());
        } else {
            $responseParams = Arr::add($responseParams, 'results', new \stdClass());
        }
        if (!is_null($response->getMessage())) {
            $responseParams = Arr::add($responseParams, 'message', $response->getMessage());
        }
        if (count($response->getError())) {
            $responseParams = Arr::add($responseParams, 'errors', $response->getError());
        } else {
            $responseParams = Arr::add($responseParams, 'errors', new \stdClass());
        }
        return response()->json($responseParams, $response->getstatusCode(), $header, JSON_UNESCAPED_UNICODE);
    }
}