<?php namespace Pisa\Api\Gizmo\Contracts;

interface Identifiable
{
    public function getPrimaryKeyValue();
    public function getPrimaryKey();
}
