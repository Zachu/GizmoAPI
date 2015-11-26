<?php namespace Pisa\Api\Gizmo\Contracts;

interface Container
{
    /**
     * Register a binding with the container
     * @param  string|array          $abstract  If given an array, expect it to be an alias
     * @param  \Closure|string|null  $concrete  If given null, try to resolve $abstract
     * @param  bool                  $shared    If the created instance should be shared
     * @return void
     * @api
     */
    public function bind($abstract, $concrete = null, $shared = false);

    /**
     * Register a shared binding with the container
     * @param  string|array          $abstract  If given an array, expect it to be an alias
     * @param  \Closure|string|null  $concrete  If given null, try to resolve $abstract
     * @return void
     * @api
     */
    public function singleton($abstract, $concrete = null);

    /**
     * Resolve a binding.
     * @param  string $abstract   String to resolve
     * @param  array  $parameters Parameters to create the new object with
     * @return mixed
     * @api
     */
    public function make($abstract, array $parameters = []);
}
