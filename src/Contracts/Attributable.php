<?php namespace Pisa\Api\Gizmo\Contracts;

interface Attributable
{
    /**
     * Set all attributes. Use AttributeMutators if presented.
     * @param  array  $attributes
     * @return void
     */
    public function fill(array $attributes);

    /**
     * Get a single attribute
     * @param  string $key
     * @return mixed Attribute value
     */
    public function getAttribute($key);

    /**
     * Get all attributes
     * @return array
     */
    public function getAttributes();

    /**
     * Set a single attribute. Use mutator if presented
     * @param string $key
     * @param mixed  $value
     */
    public function setAttribute($key, $value);

    /**
     * Alias for getAttributes
     */
    public function toArray();
}
