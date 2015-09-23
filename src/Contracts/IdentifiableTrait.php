<?php namespace Pisa\Api\Gizmo\Contracts;

trait IdentifiableTrait
{
    protected $primaryKey = 'Id';

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
}
