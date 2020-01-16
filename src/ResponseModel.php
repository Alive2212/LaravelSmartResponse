<?php
/**
 * Created by PhpStorm.
 * User: alive
 * Date: 1/11/18
 * Time: 6:56 AM
 */

namespace Alive2212\LaravelSmartResponse;

use Alive2212\ArrayHelper\ArrayHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ResponseModel
{
    protected $message;
    protected $data;
    protected $error;
    protected $statusCode;

    public function __construct()
    {
        $this->data = new Collection();
        $this->message = '';
        $this->error = array();
        $this->statusCode = 200;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    /**
     * @param Collection $collection
     */
    public function setData(Collection $collection)
    {
        $this->data = $collection;
    }

    /**
     * @return array
     */
    public function getError():array
    {
        return $this->error;
    }

    /**
     * @param array $error
     */
    public function setError(array $error)
    {
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getStatusCode():int
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param Collection $data
     * @param array $params
     */
    public function setBeautifulData(Collection $data, array $params)
    {
        $result = [];
        foreach ($data->get('data') as $datum) {
            $innerResult = [];
            foreach ($params as $paramKey => $paramValue) {
                if (is_array($paramValue)) {
                    $innerResultArray = [];
                    foreach ($paramValue as $innerParamKey => $innerParamValues) {
                        $innerData = (new ArrayHelper())->getDeep($datum, $innerParamKey);
                        foreach ($innerData as $innerDatum) {
                            $tempArray = [];
                            foreach ($innerParamValues as $innerParamValueKey => $innerParamValueValue) {
                                $tempArray = Arr::add($tempArray, $innerParamValueKey, (new ArrayHelper())->getDeep($innerDatum, $innerParamValueValue));
                            }
                            array_push($innerResultArray, $tempArray);
                        }
                    }
                    if (collect($innerResultArray)->count()) {
                        $innerResult = Arr::add($innerResult, $paramKey, $innerResultArray);
                    } else {
                        $innerResult = Arr::add($innerResult, $paramKey, null);
                    }
                } else {
                    $innerResult = Arr::add($innerResult, $paramKey, (new ArrayHelper())->getDeep($datum, $paramValue));
                }
            }
            array_push($result, $innerResult);
        }
        $data->offsetSet('data', $result);
        $this->data = $data;
    }
}