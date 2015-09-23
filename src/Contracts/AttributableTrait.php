<?php namespace Pisa\Api\Gizmo\Contracts;

trait AttributableTrait
{
    protected $attributes = [];

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
    public function getAttributes()
    {
        return $this->attributes;
    }

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

    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = 'set' . $key . 'Attribute';
            $this->{$method}($value);
        } else {
            $this->attributes[$key] = $value;
        }
    }

    public function toArray()
    {
        return $this->attributes;
    }

    protected function hasSetMutator($key)
    {
        return method_exists($this, 'set' . $key . 'Attribute');
    }

    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get' . $key . 'Attribute');
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    public function __toString()
    {
        return json_encode($this->attributes);
    }
}
