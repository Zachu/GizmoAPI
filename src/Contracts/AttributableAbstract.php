<?php namespace Pisa\Api\Gizmo\Contracts;

abstract class AbstractAttributable implements Attributable
{
    protected $attributes = [];

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /** @ignore */
    protected function hasSetMutator($key)
    {
        return method_exists($this, 'set' . $key . 'Attribute');
    }

    /** @ignore */
    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get' . $key . 'Attribute');
    }

    /** @ignore */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /** @ignore */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /** @ignore */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /** @ignore */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /** @ignore */
    public function __toString()
    {
        return json_encode($this->attributes);
    }
}
