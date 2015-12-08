<?php namespace Pisa\GizmoAPI\Models;

use Exception;
use Illuminate\Contracts\Validation\Factory as Validator;
use Pisa\GizmoAPI\Contracts\HttpClient;

abstract class BaseModel implements BaseModelInterface
{
    use \Pisa\GizmoAPI\Contracts\AttributableTrait;
    use \Pisa\GizmoAPI\Contracts\IdentifiableTrait;

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
     * Rules to validate the model instance by
     * @var array
     */
    protected $rules = [];

    /**
     * Attributes that are already sent to the service
     * @var array
     * @internal
     */
    protected $savedAttributes = [];

    /** @ignore */
    protected $validator;

    /** @ignore */
    protected $validatorFactory;

    /**
     * Make a new model instance
     * @param HttpClient $client     HTTP client
     * @param Validator  $validator  Model validator
     * @param array      $attributes Attributes to initialize
     */
    public function __construct(HttpClient $client, Validator $validatorFactory, array $attributes = array())
    {
        $this->client = $client;
        $this->load($attributes);

        $this->validatorFactory = $validatorFactory;
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

    public function getInvalid()
    {
        $this->validate();
        return $this->validator->failed();
    }

    public function getValidator()
    {
        $this->validate();
        return $this->validator;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function mergeRules(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    /**
     * @todo How the created/uncreated should begul handled?
     */
    public function isSaved()
    {
        return (empty($this->changed()) && $this->exists());
    }

    public function validate()
    {
        try {
            $this->validator = $this->validatorFactory->make($this->getAttributes(), $this->rules);
            if (!$this->validator instanceof \Illuminate\Contracts\Validation\Validator) {
                throw new Exception("Validator factory failed to make validator");
            }

            return $this->validator->fails();
        } catch (Exception $e) {
            var_dump($e->getMessage());
            throw new Exception("Unable to validate: " . $e->getMessage());
        }
    }

    public function isValid()
    {
        return !$this->validate();
    }

    public function load(array $attributes)
    {
        $this->fill($attributes);
        $this->savedAttributes = $this->attributes;
    }

    public function save()
    {
        if ($this->isValid() === false) {
            throw new Exception(
                'Unable to save model: Model instance has invalid fields (' .
                implode(', ', array_keys($this->getInvalid())) .
                ')');
        }

        $return = null;
        if ($this->exists()) {
            $return = $this->update();
        } else {
            $return = $this->create();
        }

        $this->savedAttributes = $this->attributes;
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
