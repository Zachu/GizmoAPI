<?php namespace Pisa\Api\Gizmo\Contracts;

interface Attributable
{
    /**
     * @todo
     * @param  array  $attributes [description]
     * @return [type]             [description]
     */
    public function fill(array $attributes);

    /**
     * @todo
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function getAttribute($key);

    /**
     * @todo
     * @return [type] [description]
     */
    public function getAttributes();

    /**
     * @todo
     * @param [type] $key   [description]
     * @param [type] $value [description]
     */
    public function setAttribute($key, $value);

    /**
     * @todo
     * @return [type] [description]
     */
    public function toArray();
}
