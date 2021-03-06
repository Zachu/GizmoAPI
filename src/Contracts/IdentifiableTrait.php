<?php namespace Pisa\GizmoAPI\Contracts;

trait IdentifiableTrait
{
    /**
     * Primary key column
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * Gets the primary key
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Gets the value of the primary key
     * @return mixed
     */
    public function getPrimaryKeyValue()
    {
        if (isset($this->{$this->primaryKey})) {
            return $this->{$this->primaryKey};
        } else {
            return null;
        }
    }
}
