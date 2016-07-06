<?php namespace Pisa\GizmoAPI\Contracts;

trait AttributableTrait
{
    /**
     * Attributes as a KeyValue array.
     * @var array
     */
    protected $attributes = [];

    /** @ignore */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /** @ignore */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /** @ignore */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /** @ignore */
    public function __toString()
    {
        return json_encode($this->attributes);
    }

    /** @ignore */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Set all attributes. Use AttributeMutators if presented.
     * @param  array  $attributes
     * @return void
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Get a single attribute
     * @param  string $key
     * @return mixed Attribute value
     */
    public function getAttribute($key)
    {
        if ($this->hasGetMutator($key)) {
            $method = 'get' . $key . 'Attribute';
            return $this->{$method}();
        } elseif (isset($this->$key)) {
            return $this->attributes[$key];
        } else {
            return null;
        }
    }

    /**
     * Get all attributes
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set a single attribute. Use mutator if presented
     * @param string $key
     * @param mixed  $value
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = 'set' . $key . 'Attribute';
            $this->{$method}($value);
        } else {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Alias for getAttributes
     * @uses getAttributes
     */
    public function toArray()
    {
        return $this->getAttributes();
    }

    /** @ignore */
    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get' . $key . 'Attribute');
    }

    /** @ignore */
    protected function hasSetMutator($key)
    {
        return method_exists($this, 'set' . $key . 'Attribute');
    }
}
