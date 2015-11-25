<?php namespace Pisa\Api\Gizmo\Contracts;

abstract class IdentifiableAbstract implements Identifiable
{
    /**
     * Primary key column
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
}
