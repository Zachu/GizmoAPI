<?php namespace Pisa\Api\Gizmo\Contracts;

interface Attributable
{
    public function fill(array $attributes);
    public function getAttribute($key);
    public function getAttributes();
    public function setAttribute($key, $value);
    public function toArray();
}
