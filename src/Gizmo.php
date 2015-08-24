<?php namespace Pisa\Api\Gizmo;

use zachu\zioc\IoC;
use Exception;

class Gizmo
{
    protected $repositories = [
        'users' => null,
        'hosts' => null,
        'news' => null,
        'sessions' => null,
    ];
    protected $container;

    public function __construct(IoC $container)
    {
        $this->container = $container;
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
            if ($this->initializeRepository($name) === true) { //Succesfull initialization
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
                $repository = $this->container->make('UserRepository');
                break;
            case 'hosts':
                $repository = $this->container->make('HostRepository');
                break;
            case 'news':
                $repository = $this->container->make('NewsRepository');
                break;
            case 'sessions':
                $repository = $this->container->make('SessionsRepository');
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
