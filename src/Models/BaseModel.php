<?php namespace Pisa\Api\Gizmo\Models;

use Pisa\Api\Gizmo\Contracts\HttpClient;

abstract class BaseModel implements BaseModelInterface
{
    use \Pisa\Api\Gizmo\Contracts\AttributableTrait;
    use \Pisa\Api\Gizmo\Contracts\IdentifiableTrait;

    /** @ignore */
    protected $client;

    /**
     * Let these attributes be filled any time
     * @var array
     * @todo  needed? used even?
     */
    protected $fillable = [];

    /**
     * Don't let these be filled unless its an empty field
     * @var $guarded
     * @todo  needed? used even?
     */
    protected $guarded = [];

    /**
     * Attributes that are already sent to the service
     * @var array
     * @internal
     */
    protected $savedAttributes = [];

    /**
     * Make a new model instance
     * @param HttpClient $client     HTTP client
     * @param array             $attributes Attributes to initialize
     */
    public function __construct(HttpClient $client, array $attributes = array())
    {
        $this->client = $client;
        $this->load($attributes);
    }

    /**
     * Delete the model instance
     * @return BaseModel Return $this for chaining.
     */
    abstract public function delete();

    /**
     * Check if model exists.
     *
     * Checks that the primary key is set and is not empty.
     *
     * @return boolean
     */
    public function exists()
    {
        return (isset($this->{$this->primaryKey}) && $this->{$this->primaryKey});
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @todo How the created/uncreated should be handled?
     */
    public function isSaved()
    {
        return (empty($this->changed()) && $this->exists());
    }

    /**
     * Load model attributes and mark them as saved.
     * @param  array  $attributes Attributes to be loaded
     * @return void
     */
    public function load(array $attributes)
    {
        $this->fill($attributes);
        $this->savedAttributes = $this->attributes;
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function save()
    {
        $return = null;
        if ($this->exists()) {
            $return = $this->update();
        } else {
            $return = $this->create();
        }

        $this->savedAttributes = $this->attributes;

        if ($this instanceof Cacheable) {
            $this->saveCached($this->cacheTime);
        }

        return $return;
    }

    /**
     * Check which attributes are changed but not saved
     * @return array Array of chanced attributes
     */
    protected function changed()
    {
        return array_diff_assoc($this->attributes, $this->savedAttributes);
    }

    /**
     * Create a new model instance.
     *
     * @internal  Use $this->save() for really creating a new model.
     * @return BaseModel Return $this for chaining.
     */
    abstract protected function create();

    /**
     * Check if key is fillable
     * @param  string  $key Key to check
     * @return boolean
     * @todo Check how fillable should work with guarded. Or if them both is even needed
     */
    protected function isFillable($key)
    {
        $exists   = (isset($this->attributes[$key]));
        $guarded  = in_array($key, $this->guarded);
        $fillable = in_array($key, $this->fillable);

        return ($fillable || (!$exists && $guarded));
    }

    /**
     * Convert all possible strings that the service might produce to php boolean
     * @internal Intended for models internal usage
     * @param  mixed   $var string to be converted
     * @return boolean
     */
    protected static function toBool($var)
    {
        if (!is_string($var)) {
            return (bool) $var;
        }
        switch (strtolower($var)) {
            case '1':
            case 'true':
            case 'on':
            case 'yes':
            case 'y':
                return true;
            default:
                return false;
        }
    }

    /**
     * Update the model instance.
     *
     * @internal  Use $this->save() for really updating a new model.
     * @return BaseModel Return $this for chaining.
     */
    abstract protected function update();
}
