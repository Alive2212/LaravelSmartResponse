<?php
/**
 * Created by PhpStorm.
 * User: alive
 * Date: 1/11/18
 * Time: 6:56 AM
 */

namespace Alive2212\LaravelSmartResponse;

use Alive2212\ArrayHelper\ArrayHelper;
use Illuminate\Support\Collection;

class ResponseModel
{
    protected $message;
    protected $data;
    protected $error;
    protected $status;
    protected $statusCode;

    public function __construct()
    {
        $this->data = new Collection();
        $this->message = '';
        $this->error = null;
        $this->status = "true";
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
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param null $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param mixed $data
     * @param $params
     */
    public function setBeautifulData(Collection $data, $params)
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
                                $tempArray = array_add($tempArray, $innerParamValueKey, (new ArrayHelper())->getDeep($innerDatum, $innerParamValueValue));
                            }
                            array_push($innerResultArray, $tempArray);
                        }
                    }
                    if (collect($innerResultArray)->count()) {
                        $innerResult = array_add($innerResult, $paramKey, $innerResultArray);
                    } else {
                        $innerResult = array_add($innerResult, $paramKey, null);
                    }
                } else {
                    $innerResult = array_add($innerResult, $paramKey, (new ArrayHelper())->getDeep($datum, $paramValue));
                }
            }
            array_push($result, $innerResult);
        }
        $data->offsetSet('data', $result);
        $this->data = $data;
    }

}