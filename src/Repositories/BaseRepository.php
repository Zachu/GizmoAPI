<?php namespace Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Contracts\Container;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Exceptions\InternalException;
use Pisa\GizmoAPI\Models\BaseModelInterface as BaseModel;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /** @var HttpClient */
    protected $client;

    /** @var Container */
    protected $ioc;

    /**
     * Model that the repository represents
     * @var string
     */
    protected $model;

    /**
     * Namespace of the model that the repository represents
     * @var string
     */
    protected $modelNamespace = 'Pisa\\GizmoAPI\\Models\\';

    /**
     * @param Container  $ioc    Implementation of the ioc container
     * @param HttpClient $client Implemention of http client
     */
    public function __construct(Container $ioc, HttpClient $client)
    {
        $this->client = $client;
        $this->ioc    = $ioc;

        if (!isset($this->model)) {
            throw new InternalException(get_class($this)
                . ' should have $model property set');
        }
    }

    abstract public function all($limit = 30, $skip = 0, $orderBy = null);

    public static function criteriaToFilter(array $criteria, $caseSensitive = false)
    {
        $filter = [];
        foreach ($criteria as $key => $value) {
            if (is_string($value)) {
                if (!$caseSensitive) {
                    $value    = strtolower($value);
                    $filter[] = "substringof('{$value}',tolower($key)) eq true";
                } else {
                    $filter[] = "substringof('{$value}',$key)";
                }
            } elseif (is_int($value)) {
                $filter[] = "{$key} eq {$value}";
            } elseif (is_bool($value)) {
                $filter[] = "{$key} eq " . ($value ? 'true' : 'false');
            }
        }
        $filter = implode(' or ', $filter);

        return $filter;
    }

    abstract public function findBy(
        array $criteria,
        $caseSensitive = false,
        $limit = 30,
        $skip = 0,
        $orderBy = null
    );

    abstract public function findOneBy(array $criteria, $caseSensitive = false);

    /**
     * Return the fully qualified model name.
     * @return string Fully qualified name
     * @internal
     */
    public function fqnModel()
    {
        return rtrim($this->modelNamespace, '\\') . '\\' . $this->model;
    }

    abstract public function get($id);

    abstract public function has($id);

    /**
     * @uses \Pisa\GizmoAPI\Models\BaseModel::load()  For inputting the attributes
     */
    public function make(array $attributes)
    {
        $model = $this->ioc->make($this->fqnModel());

        if ($model instanceof BaseModel) {
            $model->load($attributes);
        }

        return $model;
    }

    /**
     * Makes multiple model entries
     * @param  array  $data Array of attributes
     * @return array        Array of made models
     * @uses   \Pisa\GizmoAPI\Models\BaseModel::make()
     */
    protected function makeArray(array $data)
    {
        $array = [];
        foreach ($data as $row) {
            $array[] = $this->make($row);
        }

        return $array;
    }
}
