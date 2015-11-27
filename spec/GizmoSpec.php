<?php
namespace spec\Pisa\GizmoAPI;

use PhpSpec\ObjectBehavior;
use Pisa\GizmoAPI\Contracts\Container;
use Pisa\GizmoAPI\Repositories;

class GizmoSpec extends ObjectBehavior
{
    public function Let(Container $ioc)
    {
        $this->beConstructedWith([], $ioc);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Gizmo');
    }

    public function it_should_return_users_repository(Container $ioc, Repositories\UserRepositoryInterface $repository)
    {
        $this->hasRepository('users')->shouldBe(true);
        $ioc->make(Repositories\UserRepositoryInterface::class)->willReturn($repository);
        $this->users->shouldBe($repository);
    }

    public function it_should_return_hosts_repository(Container $ioc, Repositories\HostRepositoryInterface $repository)
    {
        $this->hasRepository('hosts')->shouldBe(true);
        $ioc->make(Repositories\HostRepositoryInterface::class)->willReturn($repository);
        $this->hosts->shouldBe($repository);
    }

    public function it_should_return_news_repository(Container $ioc, Repositories\NewsRepositoryInterface $repository)
    {
        $this->hasRepository('news')->shouldBe(true);
        $ioc->make(Repositories\NewsRepositoryInterface::class)->willReturn($repository);
        $this->news->shouldBe($repository);
    }

    public function it_should_return_sessions_repository(Container $ioc, Repositories\SessionsRepositoryInterface $repository)
    {
        $this->hasRepository('sessions')->shouldBe(true);
        $ioc->make(Repositories\SessionsRepositoryInterface::class)->willReturn($repository);
        $this->sessions->shouldBe($repository);
    }

    public function it_should_throw_on_unknown_repository()
    {
        $repository = 'unknown';
        $this->hasRepository($repository)->shouldBe(false);
        $this->shouldThrow('\Exception')->duringGetRepository($repository);
    }

    public function it_should_set_and_get_config_values()
    {
        $config     = ['setting1' => 'value1'];
        $key        = key($config);
        $newSetting = ['newSetting' => 'newValue'];
        $newKey     = key($newSetting);
        $this->beConstructedWith($config);

        $this->getConfig()->shouldBe($config);
        $this->getConfig($key)->shouldBe($config[$key]);

        $this->setConfig($newKey, $newSetting[$newKey]);
        $this->getConfig($newKey)->shouldBe($newSetting[$newKey]);
    }
}
