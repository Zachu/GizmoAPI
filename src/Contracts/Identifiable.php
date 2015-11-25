<?php namespace Pisa\Api\Gizmo\Contracts;

interface Identifiable
{
    /**
     * Gets the value of the primary key
     * @return mixed
     */
    public function getPrimaryKeyValue();

    /**
     * Gets the primary key
     * @return string
     */
    public function getPrimaryKey();
}
