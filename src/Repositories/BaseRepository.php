<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;
use Illuminate\Contracts\Container\Container;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Models\BaseModelInterface as BaseModel;
use zachu\zioc\IoC;

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
        $model = $this->ioc->make(rtrim($this->modelNamespace, '\\') . '\\' . $this->model);

        if ($model instanceof BaseModel) {
            $model->load($attributes);
        }

        return $model;
    }

    protected function makeArray(array $data)
    {
        $array = [];
        foreach ($data as $row) {
            $array[] = $this->make($row);
        }

        return $array;
    }

    protected function criteriaToFilter(array $criteria, $caseSensitive = false)
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
