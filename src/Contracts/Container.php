<?php namespace Pisa\GizmoAPI\Contracts;

interface Container
{
    /**
     * Register a binding with the container
     * @param  string|array   $abstract  If given an array, expect it to be an alias
     * @param  callable|null  $concrete  If given null, try to resolve $abstract
     * @param  bool           $shared    If the created instance should be shared
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false);

    /**
     * Resolve a binding.
     * @param  string  $abstract   String to resolve
     * @param  array   $parameters Parameters to create the new object with
     * @return mixed
     */
    public function make($abstract, array $parameters = []);

    /**
     * Register a shared binding with the container
     * @param  string|array   $abstract  If given an array, expect it to be an alias
     * @param  callable|null  $concrete  If given null, try to resolve $abstract
     * @return void
     */
    public function singleton($abstract, $concrete = null);
}
