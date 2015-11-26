<?php namespace Pisa\Api\Gizmo\Adapters;

use Illuminate\Container\Container as ConcreteContainer;
use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Pisa\Api\Gizmo\Contracts\Container;

/**
 * Illuminate Container Adapter
 */
class IlluminateContainerAdapter implements Container
{
    /** @var IlluminateContainer */
    protected $container;

    /**
     * Create a container
     * @param IlluminateContainer|null $container If no container is given, one is created automatically
     */
    public function __construct(IlluminateContainer $container = null)
    {
        if ($container === null) {
            $this->container = new ConcreteContainer;
        } else {
            $this->container = $container;
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->container->bind($abstract, $concrete, $shared);
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function make($abstract, array $parameters = [])
    {
        return $this->container->make($abstract, $parameters);
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->container->singleton($abstract, $concrete);
    }
}
