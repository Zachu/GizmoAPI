<?php namespace Pisa\GizmoAPI\Contracts;

interface Identifiable
{
    /**
     * Gets the primary key
     * @return string
     */
    public function getPrimaryKey();

    /**
     * Gets the value of the primary key
     * @return mixed
     */
    public function getPrimaryKeyValue();
}
