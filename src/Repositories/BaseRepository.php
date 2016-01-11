<?php namespace Pisa\GizmoAPI\Repositories;

use Exception;
use Pisa\GizmoAPI\Contracts\Container;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Models\BaseModelInterface as BaseModel;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /** @ignore */
    protected $client;

    /** @ignore */
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

    public function __construct(Container $ioc, HttpClient $client)
    {
        $this->client = $client;
        $this->ioc    = $ioc;

        if (!isset($this->model)) {
            throw new Exception(get_class($this) . ' should have $model');
        }
    }

    abstract public function all($limit = 30, $skip = 0, $orderBy = null);

    /**
     * Turn array of criteria into an OData filter
     *
     * @param  array   $criteria      Array of criteria
     * @param  boolean $caseSensitive Is the search supposed to be case sensitive. Defaults to false.
     * @return string                 Returns string to be put on the OData $filter
     * @internal
     */
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
            }
        }
        $filter = implode(' or ', $filter);

        return $filter;
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    abstract public function findBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null);

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    abstract public function findOneBy(array $criteria, $caseSensitive = false);

    /**
     * Return the fully qualified model name.
     * @return string Fully qualified name
     */
    public function fqnModel()
    {
        return rtrim($this->modelNamespace, '\\') . '\\' . $this->model;
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    abstract public function get($id);

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    abstract public function has($id);

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
     * @uses   self::make   for making a single instance
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
