<?php namespace Pisa\Api\Gizmo\Models;

abstract class BaseModel implements BaseModelInterface
{
    //@todo how do these work with eachother? If a field isn't fillable nor guarded, or if it's both?
    /**
     * @var $fillable
     * Let these attributes be filled any time
     */
    protected $fillable = [];

    /**
     * @var $guarded
     * Don't let these be filled unless its an empty field
     */
    protected $guarded = [];

    protected $attributes      = [];
    protected $savedAttributes = [];
    protected $primaryKey      = 'Id';

    /**
     * Used with method save(). Use that when creating a new model.
     */
    abstract protected function create();

    /**
     * Used with method save(). Use that when updating your model.
     */
    abstract protected function update();

    abstract public function delete();

    public function load(array $attributes, $skipChecks = false)
    {
        $this->fill($attributes, $skipChecks);
        $this->savedAttributes = $this->attributes;
    }

    protected function changed()
    {
        return array_diff_assoc($this->attributes, $this->savedAttributes);
    }

    public function fill(array $attributes, $skipChecks = false)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value, $skipChecks);
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

    public function getPrimaryKeyValue()
    {
        if (isset($this->{$this->primaryKey})) {
            return $this->{$this->primaryKey};
        } else {
            return null;
        }
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function setAttribute($key, $value, $skipChecks = false)
    {
        if ($this->isFillable($key) || $skipChecks) {
            if ($this->hasSetMutator($key)) {
                $method = 'set' . $key . 'Attribute';
                $this->{$method}($value);
            } else {
                $this->attributes[$key] = $value;
            }
        }
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function exists()
    {
        return (isset($this->{$this->primaryKey}) && $this->{$this->primaryKey});
    }

    public function isSaved()
    {
        return (empty($this->changed()) && $this->exists());
    }

    public function save()
    {
        $return = null;
        if ($this->exists()) {
            $return = $this->update();
        } else {
            $return = $this->create();
        }

        $this->savedAttributes = $this->attributes;
        return $return;
    }

    protected function hasSetMutator($key)
    {
        return method_exists($this, 'set' . $key . 'Attribute');
    }

    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get' . $key . 'Attribute');
    }

    protected function isFillable($key)
    {
        $exists   = (isset($this->attributes[$key]));
        $guarded  = in_array($key, $this->guarded);
        $fillable = in_array($key, $this->fillable);

        return ($fillable || (!$exists && $guarded));
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

    protected function toBool($var)
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
}
