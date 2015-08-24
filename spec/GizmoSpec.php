<?php
namespace spec\Pisa\Api\Gizmo;

use PhpSpec\ObjectBehavior;
use Pisa\Api\Gizmo\Repositories\HostRepository;
use Pisa\Api\Gizmo\Repositories\NewsRepository;
use Pisa\Api\Gizmo\Repositories\SessionsRepository;
use Pisa\Api\Gizmo\Repositories\UserRepository;
use zachu\zioc\IoC;

class GizmoSpec extends ObjectBehavior
{
    public function Let(IoC $ioc)
    {
        $this->beConstructedWith($ioc);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Gizmo');
    }

    public function it_should_return_users_repository(IoC $ioc, UserRepository $repository)
    {
        $this->hasRepository('users')->shouldBe(true);
        $ioc->make('UserRepository')->willReturn($repository);
        $this->users->shouldBe($repository);
    }

    public function it_should_return_hosts_repository(IoC $ioc, HostRepository $repository)
    {
        $this->hasRepository('hosts')->shouldBe(true);
        $ioc->make('HostRepository')->willReturn($repository);
        $this->hosts->shouldBe($repository);
    }

    public function it_should_return_news_repository(IoC $ioc, NewsRepository $repository)
    {
        $this->hasRepository('news')->shouldBe(true);
        $ioc->make('NewsRepository')->willReturn($repository);
        $this->news->shouldBe($repository);
    }

    public function it_should_return_sessions_repository(IoC $ioc, SessionsRepository $repository)
    {
        $this->hasRepository('sessions')->shouldBe(true);
        $ioc->make('SessionsRepository')->willReturn($repository);
        $this->sessions->shouldBe($repository);
    }
}
