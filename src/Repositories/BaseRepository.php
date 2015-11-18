<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Adapters\HttpResponseAdapter;
use Pisa\Api\Gizmo\Contracts\Container;
use Pisa\Api\Gizmo\Models\BaseModelInterface as BaseModel;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $client;
    protected $model;
    protected $modelNamespace = 'Pisa\\Api\\Gizmo\\Models\\';
    protected $ioc;

    public function __construct(Container $ioc, HttpClient $client)
    {
        $this->client = $client;
        $this->ioc    = $ioc;

        if (!isset($this->model)) {
            throw new Exception(get_class($this) . ' should have $model');
        }
    }

    public function make(array $attributes)
    {
        $model = $this->ioc->make($this->fqnModel());

        if ($model instanceof BaseModel) {
            $model->load($attributes);
        }

        return $model;
    }

    public function fqnModel()
    {
        return rtrim($this->modelNamespace, '\\') . '\\' . $this->model;
    }

    protected function makeArray(array $data)
    {
        $array = [];
        foreach ($data as $row) {
            $array[] = $this->make($row);
        }

        return $array;
    }

    public static function checkResponseStatusCodes(HttpResponseAdapter $response, $statusCodes = [])
    {
        if (is_numeric($statusCodes)) {
            $statusCodes = [(int) $statusCodes];
        }

        if (!in_array($response->getStatusCode(), $statusCodes)) {
            throw new Exception("Unexpected HTTP Code " . $response->getStatusCode() . ". Expecting " . implode(',', $statusCodes));
        }
    }

    public static function checkResponseBoolean(HttpResponseAdapter $response)
    {
        if (!is_bool($response->getBody())) {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting boolean");
        }
    }

    public static function checkResponseArray(HttpResponseAdapter $response)
    {
        if (!is_array($response->getBody())) {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting array");
        }
    }

    public static function checkResponseInteger(HttpResponseAdapter $response)
    {
        if (!is_int($response->getBody())) {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting integer");
        }
    }

    public static function checkResponseEmpty(HttpResponseAdapter $response)
    {
        if ($response->getBody() != '') {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting none");
        }
    }

    public static function criteriaToFilter(array $criteria, $caseSensitive = false)
    {
        $filter = [];
        foreach ($criteria as $key => $value) {
            if (!$caseSensitive) {
                $value    = strtolower($value);
                $filter[] = "substringof('{$value}',tolower($key)) eq true";
            } else {
                $filter[] = "substringof('{$value}',$key)";
            }

        }
        $filter = implode(' or ', $filter);

        return $filter;
    }
}
