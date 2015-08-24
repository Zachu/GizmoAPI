<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Models\BaseModelInterface;
use zachu\zioc\IoC;

class BaseRepository
{
    protected $client;
    protected $model;
    protected $ioc;

    public function __construct(HttpClient $client, IoC $ioc)
    {
        $this->client = $client;
        $this->ioc = $ioc;

        if (!isset($this->model)) {
            throw new Exception(get_class($this) . ' should have $model');
        }
    }

    /*public function save(BaseModelInterface $model)
    {
    if ($model->exists()) {
    $this->update($model);
    } else {
    $this->create($model);
    }
    }*/

    public function make(array $attributes)
    {
        $model = $this->ioc->make($this->model); //@todo not testable :( Damn. What do?
        if ($model instanceof BaseModelInterface) {
            $model->fill($attributes, true);
        } else {
            throw new Exception("Unable to make object because it's not instance of BaseModel");
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
                $value = strtolower($value);
                $filter[] = "substringof('{$value}',tolower($key)) eq true";
            } else {
                $filter[] = "substringof('{$value}',$key)";
            }
        }

        $filter = implode(' or ', $filter);

        return $filter;
    }
}
