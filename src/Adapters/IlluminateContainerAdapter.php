<?php namespace Pisa\Api\Gizmo\Adapters;

use Illuminate\Container\Container as ConcreteContainer;
use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Pisa\Api\Gizmo\Contracts\Container;

/**
 * Illuminate Container Adapter
 */
class IlluminateContainerAdapter implements Container
{
    protected $container;

    public function __construct(IlluminateContainer $container = null)
    {
        if ($container === null) {
            $this->container = new ConcreteContainer;
        } else {
            $this->container = $container;
        }
    }

    /**
     * Register a shared binding in the container.
     *
     * @param  string|array  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->container->singleton($abstract, $concrete);
    }

    /**
     * Register a binding with the container
     * @param  string|array  $abstract
     * @param  \Closure|string|null  $concrete
     * @param  bool  $shared
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->container->bind($abstract, $concrete, $shared);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string  $abstract
     * @param  array   $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        return $this->container->make($abstract, $parameters);
    }
}
