<?php namespace Pisa\GizmoAPI\Contracts;

abstract class IdentifiableAbstract implements Identifiable
{
    /**
     * Primary key column
     * @var string
     */
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
