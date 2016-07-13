<?php namespace Pisa\GizmoAPI\Models;

use Psr\Log\LoggerInterface;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Exceptions\InternalException;
use Pisa\GizmoAPI\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Factory as Validator;

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

    /** @ignore  */
    protected $logger;

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
    public function __construct(
        HttpClient $client,
        Validator $validatorFactory,
        LoggerInterface $logger,
        array $attributes = []
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->load($attributes);

        $this->validatorFactory = $validatorFactory;
    }

    public function __toString()
    {
        $className = get_class($this);
        if (($pos = strrpos($className, '\\')) !== false) {
            $className = substr($className, $pos + 1);
        }

        return $className
        . '[' . $this->getPrimaryKey() . '='
        . $this->getPrimaryKeyValue() . ']';
    }

    abstract public function delete();

    public function exists()
    {
        return (isset($this->{$this->primaryKey}) && $this->{$this->primaryKey});
    }

    public function getInvalid()
    {
        $this->validate();
        return $this->validator->failed();
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getValidator()
    {
        $this->validate();
        return $this->validator;
    }

    /**
     * @todo How the created/uncreated should be handled?
     */
    public function isSaved()
    {
        return (empty($this->changed()) && $this->exists());
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

    public function mergeRules(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    public function save()
    {
        if ($this->isValid() === false) {
            throw new ValidationException(
                'Unable to save model: Model instance has invalid fields (' .
                implode(', ', array_keys($this->getInvalid())) . ')'
            );
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

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function validate()
    {
        $this->validator = $this->validatorFactory->make($this->getAttributes(), $this->rules);
        if (!$this->validator instanceof \Illuminate\Contracts\Validation\Validator) {
            throw new InternalException("Validator factory failed to make validator");
        }

        return $this->validator->fails();
    }

    /**
     * Check which attributes are changed but not saved
     * @return array Array of chanced attributes
     */
    protected function changed()
    {
        return array_udiff_assoc($this->attributes, $this->savedAttributes, function ($a, $b) {
            if ($a !== $b) {
                return -1;
            } else {
                return 0;
            }
        });
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
{

}
