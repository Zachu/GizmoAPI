<?php namespace Pisa\GizmoAPI;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Pisa\GizmoAPI\Contracts\Container;
use Pisa\GizmoAPI\Exceptions\InternalException;
use Pisa\GizmoAPI\Adapters\IlluminateContainerAdapter;
use Pisa\GizmoAPI\Exceptions\InvalidArgumentException;
use Pisa\GizmoAPI\Repositories\HostRepositoryInterface;
use Pisa\GizmoAPI\Repositories\NewsRepositoryInterface;
use Pisa\GizmoAPI\Repositories\UserRepositoryInterface;
use Pisa\GizmoAPI\Repositories\ServiceRepositoryInterface;
use Pisa\GizmoAPI\Repositories\SessionRepositoryInterface;

/**
 * Gizmo Application Management Platforms API wrapper for PHP
 */
class Gizmo
{
    /** @var array */
    protected $config;

    /** @var Container */
    protected $ioc;

    /** @var array Resolved repositories */
    protected $repositories = [
        'users'    => null,
        'hosts'    => null,
        'news'     => null,
        'sessions' => null,
        'service'  => null,
    ];

    /**
     * Construct a GizmoAPI object
     * @param array          $config
     * @param Container|null $ioc    If no container is given, one is created automatically.
     */
    public function __construct(array $config = [], Container $ioc = null)
    {
        $this->config = array_merge([
            'http'       => [],
            'user.rules' => [],
            'host.rules' => [],
            'news.rules' => [],
            'logger'     => new NullLogger,
        ], $config);

        if ($ioc === null) {
            $ioc = new IlluminateContainerAdapter;
        }

        $this->ioc = $ioc;
        $this->bootstrap();
    }

    /**
     * Get a repository
     * @param  string $name Name of the repository
     * @return \Pisa\GizmoAPI\Repositories\BaseRepositoryInterface|ServiceRepositoryInterface
     * @uses   \Pisa\GizmoAPI\Gizmo::getRepository()
     */
    public function __get($name)
    {
        if ($this->hasRepository($name)) {
            return $this->getRepository($name);
        } else {
            return null;
        }
    }

    /**
     * Gets a single config value
     * @param  string $name
     * @return mixed
     */
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

    /**
     * Gets a repository. Initializes one if it's not yet initialized
     * @param  string $name Name of the repository
     * @return \Pisa\GizmoAPI\Repositories\BaseRepositoryInterface|ServiceRepositoryInterface
     * @throws \Pisa\GizmoAPI\Exceptions\InternalException on errors in initialization
     * @throws \Pisa\GizmoAPI\Exceptions\InvalidArgumentException when asked repository is not found
     */
    public function getRepository($name)
    {
        if ($this->hasRepository($name) && $this->repositoryInitialized($name)) {
            return $this->repositories[$name];
        } elseif ($this->hasRepository($name) && !$this->repositoryInitialized($name)) {
            if ($this->initializeRepository($name) === true) {
                //Succesfull initialization
                return $this->repositories[$name];
            } else {
                throw new InternalException(
                    "Repository definition found for $name but initialization failed. "
                    . 'Maybe Gizmo::initializeRepository() is missing the repository?'
                );
            }
        } else {
            throw new InvalidArgumentException("No repositories found with name $name");
        }
    }

    /**
     * Checks if such repository should exist
     * @param  string $name Name of the repository
     * @return boolean
     */
    public function hasRepository($name)
    {
        return array_key_exists($name, $this->repositories);
    }

    /**
     * Sets a config parameter
     * @param string $name
     * @param mixed  $value
     */
    public function setConfig($name, $value = null)
    {
        $this->config[$name] = $value;
    }

    /**
     * Initializes a repository and stores it in $this->repositories
     * @param  string $name Name of the repository
     * @return boolean      true if initializion succeeded, false otherwise
     * @internal
     */
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
                $repository = $this->ioc->make(SessionRepositoryInterface::class);
                break;
            case 'service':
                $repository = $this->ioc->make(ServiceRepositoryInterface::class);
                break;
        }

        if ($repository !== null) {
            $this->repositories[$name] = $repository;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if repository has been initialized yet
     * @param  string $name Name of the repository
     * @return boolean
     */
    protected function repositoryInitialized($name)
    {
        return ($this->hasRepository($name) && is_object($this->repositories[$name]));
    }

    /**
     * Bootstrap
     *
     * Bind concrete implementations to interfaces, initialize them with correct configs etc.
     * @return void
     * @internal
     */
    private function bootstrap()
    {
        $this->ioc->singleton(\Pisa\GizmoAPI\Contracts\Container::class, function ($c) {
            return $this->ioc;
        });

        $this->ioc->singleton(
            \Pisa\GizmoAPI\Contracts\HttpClient::class,
            \Pisa\GizmoAPI\Adapters\GuzzleClientAdapter::class
        );

        $this->ioc->bind(
            \Pisa\GizmoAPI\Contracts\HttpResonse::class,
            \Pisa\GizmoAPI\Adapters\GuzzleResponseAdapter::class
        );

        $this->ioc->singleton(\GuzzleHttp\ClientInterface::class, function ($c) {
            $httpConfig = ($this->getConfig('http') !== null ? $this->getConfig('http') : []);
            return new \GuzzleHttp\Client($httpConfig);
        });

        $this->ioc->bind(
            \Pisa\GizmoAPI\Repositories\UserRepositoryInterface::class,
            \Pisa\GizmoAPI\Repositories\UserRepository::class
        );
        $this->ioc->bind(
            \Pisa\GizmoAPI\Repositories\HostRepositoryInterface::class,
            \Pisa\GizmoAPI\Repositories\HostRepository::class
        );
        $this->ioc->bind(
            \Pisa\GizmoAPI\Repositories\SessionRepositoryInterface::class,
            \Pisa\GizmoAPI\Repositories\SessionRepository::class
        );
        $this->ioc->bind(
            \Pisa\GizmoAPI\Repositories\NewsRepositoryInterface::class,
            \Pisa\GizmoAPI\Repositories\NewsRepository::class
        );
        $this->ioc->bind(
            \Pisa\GizmoAPI\Repositories\ServiceRepositoryInterface::class,
            \Pisa\GizmoAPI\Repositories\ServiceRepository::class
        );

        $this->ioc->bind(\Pisa\GizmoAPI\Models\UserInterface::class, function ($c) {
            $user = $c->make(\Pisa\GizmoAPI\Models\User::class);
            $user->mergeRules($this->getConfig('user.rules'));

            return $user;
        });

        $this->ioc->bind(\Pisa\GizmoAPI\Models\HostInterface::class, function ($c) {
            $user = $c->make(\Pisa\GizmoAPI\Models\Host::class);
            $user->mergeRules($this->getConfig('host.rules'));

            return $user;
        });

        $this->ioc->bind(\Pisa\GizmoAPI\Models\NewsInterface::class, function ($c) {
            $user = $c->make(\Pisa\GizmoAPI\Models\News::class);
            $user->mergeRules($this->getConfig('news.rules'));

            return $user;
        });

        $this->ioc->bind(
            \Illuminate\Contracts\Validation\Factory::class,
            \Illuminate\Validation\Factory::class
        );
        $this->ioc->bind(\Symfony\Component\Translation\TranslatorInterface::class, function ($c) {
            return new \Symfony\Component\Translation\Translator('en');
        });

        $this->ioc->bind(\Psr\Log\LoggerInterface::class, function ($c) {
            if (!$this->getConfig('logger') instanceof LoggerInterface) {
                throw new InvalidArgumentException($this->getConfig('logger')
                    . " doesn't seem to be compatible with \Psr\Log\LoggerInterface");
            }

            return $this->getConfig('logger');
        });
    }
}
