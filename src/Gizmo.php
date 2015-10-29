<?php namespace Pisa\Api\Gizmo;

use Exception;
use Illuminate\Container\Container as ConcreteContainer;
use Illuminate\Contracts\Container\Container;
use Pisa\Api\Gizmo\Repositories\HostRepositoryInterface;
use Pisa\Api\Gizmo\Repositories\NewsRepositoryInterface;
use Pisa\Api\Gizmo\Repositories\SessionsRepositoryInterface;
use Pisa\Api\Gizmo\Repositories\UserRepositoryInterface;

class Gizmo
{
    protected $repositories = [
        'users'    => null,
        'hosts'    => null,
        'news'     => null,
        'sessions' => null,
    ];
    protected $config;
    protected $ioc;

    public function __construct(array $config = array(), Container $ioc = null)
    {
        $this->config = $config;
        if ($ioc === null) {
            $ioc = new ConcreteContainer;
        }

        $this->ioc = $ioc;
        $this->bootstrap();
    }

    private function bootstrap()
    {
        $this->ioc->singleton(\Illuminate\Contracts\Container\Container::class, function ($c) {
            return $c;
        });

        $this->ioc->singleton(\Pisa\Api\Gizmo\Adapters\HttpClientAdapter::class);
        $this->ioc->singleton(\GuzzleHttp\ClientInterface::class, function ($c) {
            $httpConfig = ($this->getConfig('http') !== null ? $this->getConfig('http') : []);
            return new \GuzzleHttp\Client($httpConfig);
        });

        $this->ioc->bind(\Pisa\Api\Gizmo\Repositories\UserRepositoryInterface::class, \Pisa\Api\Gizmo\Repositories\UserRepository::class);
        $this->ioc->bind(\Pisa\Api\Gizmo\Repositories\HostRepositoryInterface::class, \Pisa\Api\Gizmo\Repositories\HostRepository::class);
        $this->ioc->bind(\Pisa\Api\Gizmo\Repositories\SessionRepositoryInterface::class, \Pisa\Api\Gizmo\Repositories\SessionsRepository::class);
        $this->ioc->bind(\Pisa\Api\Gizmo\Repositories\NewsRepositoryInterface::class, \Pisa\Api\Gizmo\Repositories\NewsRepository::class);
    }

    public function getConfig($name = null)
    {
        if ($name === null) {
            return $this->config;
        } elseif (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return null;
        }
    }

    public function setConfig($name, $value = null)
    {
        $this->config[$name] = $value;
    }

    public function __get($name)
    {
        if ($this->hasRepository($name)) {
            return $this->getRepository($name);
        } else {
            return null;
        }
    }

    public function hasRepository($name)
    {
        return array_key_exists($name, $this->repositories);
    }

    public function getRepository($name)
    {
        if ($this->hasRepository($name) && $this->repositoryInitialized($name)) {
            return $this->repositories[$name];
        } elseif ($this->hasRepository($name) && !$this->repositoryInitialized($name)) {
            if ($this->initializeRepository($name) === true) {
                //Succesfull initialization
                return $this->repositories[$name];
            } else {
                throw new Exception("Repository definition found for $name but initialization failed");
            }
        } else {
            throw new Exception("No repositories found with name $name");
        }
    }

    protected function repositoryInitialized($name)
    {
        return ($this->hasRepository($name) && is_object($this->repositories[$name]));
    }

    protected function initializeRepository($name)
    {
        $repository = null;
        switch ($name) {
            case 'users':
                $repository = $this->ioc->make(UserRepositoryInterface::class);
                break;
            case 'hosts':
                $repository = $this->ioc->make(HostRepositoryInterface::class);
                break;
            case 'news':
                $repository = $this->ioc->make(NewsRepositoryInterface::class);
                break;
            case 'sessions':
                $repository = $this->ioc->make(SessionsRepositoryInterface::class);
                break;
        }

        if ($repository !== null) {
            $this->repositories[$name] = $repository;
            return true;
        } else {
            return false;
        }
    }
}
