<?php namespace Pisa\GizmoAPI\Adapters;

use Illuminate\Container\Container as ConcreteContainer;
use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Pisa\GizmoAPI\Contracts\Container;

/**
 * Illuminate Container Adapter
 */
class IlluminateContainerAdapter implements Container
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $container;

    /**
     * Create a container
     * @param \Illuminate\Contracts\Container\Container|null $container If no container is given, one is created automatically
     */
    public function __construct(IlluminateContainer $container = null)
    {
        if ($container === null) {
            $this->container = new ConcreteContainer;
        } else {
            $this->container = $container;
        }
    }

    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->container->bind($abstract, $concrete, $shared);
    }

    public function make($abstract, array $parameters = [])
    {
        return $this->container->make($abstract, $parameters);
    }

    public function singleton($abstract, $concrete = null)
    {
        $this->container->singleton($abstract, $concrete);
    }
}
