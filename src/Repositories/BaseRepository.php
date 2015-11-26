<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Adapters\HttpResponseAdapter;
use Pisa\Api\Gizmo\Contracts\Container;
use Pisa\Api\Gizmo\Models\BaseModelInterface as BaseModel;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @ignore
     */
    protected $client;

    /**
     * Model that the repository represents
     * @var string
     */
    protected $model;

    /**
     * Namespace of the model that the repository represents
     * @var string
     */
    protected $modelNamespace = 'Pisa\\Api\\Gizmo\\Models\\';

    /**
     * @ignore
     */
    protected $ioc;

    public function __construct(Container $ioc, HttpClient $client)
    {
        $this->client = $client;
        $this->ioc    = $ioc;

        if (!isset($this->model)) {
            throw new Exception(get_class($this) . ' should have $model');
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    abstract public function all($limit = 30, $skip = 0, $orderBy = null);

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
     * Return the fully qualified model name.
     * @return string Fully qualified name
     */
    public function fqnModel()
    {
        return rtrim($this->modelNamespace, '\\') . '\\' . $this->model;
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

    /**
     * Check that http response status codes match the codes we are expecting for.
     * @param  HttpResponseAdapter $response    Http response that was got
     * @param  int|array           $statusCodes Array of status codes to be expected. Can be a single status code too.
     * @return void
     * @throws Exception                        if the status code was unexpected
     * @internal                                Intended to use with repositories to validate the responses
     */
    protected static function checkResponseStatusCodes(HttpResponseAdapter $response, $statusCodes = [])
    {
        if (is_numeric($statusCodes)) {
            $statusCodes = [(int) $statusCodes];
        }

        if (!in_array($response->getStatusCode(), $statusCodes)) {
            throw new Exception("Unexpected HTTP Code " . $response->getStatusCode() . ". Expecting " . implode(',', $statusCodes));
        }
    }

    /**
     * Check that http response body was a boolean.
     * @param  HttpResponseAdapter $response    Http response that was got
     * @return void
     * @throws Exception                        if the body was unexpected
     * @internal                                Intended to use with repositories to validate the responses
     */
    protected static function checkResponseBoolean(HttpResponseAdapter $response)
    {
        if (!is_bool($response->getBody())) {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting boolean");
        }
    }

    /**
     * Check that http response body was an array.
     * @param  HttpResponseAdapter $response    Http response that was got
     * @return void
     * @throws Exception                        if the body was unexpected
     * @internal                                Intended to use with repositories to validate the responses
     */
    protected static function checkResponseArray(HttpResponseAdapter $response)
    {
        if (!is_array($response->getBody())) {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting array");
        }
    }

    /**
     * Check that http response body was an integer.
     * @param  HttpResponseAdapter $response    Http response that was got
     * @return void
     * @throws Exception                        if the body was unexpected
     * @internal                                Intended to use with repositories to validate the responses
     */
    protected static function checkResponseInteger(HttpResponseAdapter $response)
    {
        if (!is_int($response->getBody())) {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting integer");
        }
    }

    /**
     * Check that http response body was empty.
     * @param  HttpResponseAdapter $response    Http response that was got
     * @return void
     * @throws Exception                        if the body was unexpected
     * @internal                                Intended to use with repositories to validate the responses
     */
    protected static function checkResponseEmpty(HttpResponseAdapter $response)
    {
        if ($response->getBody() != '') {
            throw new Exception("Unexpected response body " . gettype($response->getBody()) . ". Expecting none");
        }
    }

    /**
     * Turn array of criteria into an OData filter
     * @param  array   $criteria      Array of criteria
     * @param  boolean $caseSensitive Is the search supposed to be case sensitive. Defaults to false.
     * @return string                 Returns string to be put on the OData $filter
     */
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
