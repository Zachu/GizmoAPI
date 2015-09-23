<?php namespace Pisa\Api\Gizmo\Models;

<<<<<<< master
=======
use Pisa\Api\Gizmo\Contracts\Cacheable;

>>>>>>> local
abstract class BaseModel implements BaseModelInterface
{
    use \Pisa\Api\Gizmo\Contracts\AttributableTrait;
    use \Pisa\Api\Gizmo\Contracts\IdentifiableTrait;

    // @todo how do these work with eachother? If a field isn't fillable nor guarded, or if it's both?
    /**
     * @var $fillable
     * @todo  needed? used even?
     * Let these attributes be filled any time
     */
    protected $fillable = [];

    /**
     * @var $guarded
     * @todo  needed? used even?
     * Don't let these be filled unless its an empty field
     */
    protected $guarded = [];

<<<<<<< master
    protected $attributes      = [];
    protected $savedAttributes = [];
    protected $primaryKey      = 'Id';
=======
    protected $savedAttributes = [];
>>>>>>> local

    /**
     * Used with method save(). Use that when creating a new model.
     */
    abstract protected function create();

    /**
     * Used with method save(). Use that when updating your model.
     */
    abstract protected function update();

    abstract public function delete();

    public function load(array $attributes)
    {
        $this->fill($attributes);
        $this->savedAttributes = $this->attributes;
    }

    protected function changed()
    {
        return array_diff_assoc($this->attributes, $this->savedAttributes);
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

        if ($this instanceof Cacheable) {
            $this->saveCached($this->cacheTime);
        }

        return $return;
    }

    protected function isFillable($key)
    {
        $exists   = (isset($this->attributes[$key]));
        $guarded  = in_array($key, $this->guarded);
        $fillable = in_array($key, $this->fillable);

        return ($fillable || (!$exists && $guarded));
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
