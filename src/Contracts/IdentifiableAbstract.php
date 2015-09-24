<?php namespace Pisa\Api\Gizmo\Contracts;

abstract class IdentifiableAbstract implements Identifiable
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
