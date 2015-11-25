<?php namespace Pisa\Api\Gizmo\Contracts;

trait IdentifiableTrait
{
    /**
     * Primary key column
     * @var string
     */
    protected $primaryKey = 'Id';

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

    /**
     * Gets the primary key
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
}
