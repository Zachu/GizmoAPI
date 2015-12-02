<?php

namespace spec\Pisa\GizmoAPI\Repositories;

use PhpSpec\ObjectBehavior;
use Pisa\GizmoAPI\Contracts\HttpClient;
use spec\Pisa\GizmoAPI\HttpResponses;

class ServiceRepositorySpec extends ObjectBehavior
{
    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Repositories\ServiceRepository');
    }

    public function it_should_get_time_from_service(HttpClient $client)
    {
        $client->get('Service/Time')->shouldBeCalled()->willReturn(HttpResponses::time());
        $this->getTime()->shouldBeInteger();
    }

    public function it_should_throw_on_get_time_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Time')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetTime();

        $client->get('Service/Time')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetTime();
    }

    public function it_should_stop_service(HttpClient $client)
    {
        $client->get('Service/Stop')->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->stop();
    }

    public function it_should_throw_on_stop_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Stop')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringStop();

        $client->get('Service/Stop')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringStop();
    }

    public function it_should_restart_service(HttpClient $client)
    {
        $client->get('Service/Restart')->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->restart();
    }

    public function it_should_throw_on_restart_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Restart')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringRestart();

        $client->get('Service/Restart')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringRestart();
    }

    public function it_should_get_status_from_service(HttpClient $client)
    {
        $client->get('Service/Status')->shouldBeCalled()->willReturn(HttpResponses::randomArray());
        $this->getStatus()->shouldBeArray();
    }

    public function it_should_throw_on_get_status_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Status')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetStatus();

        $client->get('Service/Status')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetStatus();
    }

    public function it_should_get_version_from_service(HttpClient $client)
    {
        $client->get('Service/Version')->shouldBeCalled()->willReturn(HttpResponses::randomString());
        $this->getVersion()->shouldBeString();
    }

    public function it_should_throw_on_get_version_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Version')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetVersion();

        $client->get('Service/Version')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetVersion();
    }

    public function it_should_get_module_from_service(HttpClient $client)
    {
        $client->get('Service/Module')->shouldBeCalled()->willReturn(HttpResponses::randomArray());
        $this->getModule()->shouldBeArray();
    }

    public function it_should_throw_on_get_module_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Module')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetModule();

        $client->get('Service/Module')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetModule();
    }

    public function it_should_get_license_from_service(HttpClient $client)
    {
        $client->get('Service/License')->shouldBeCalled()->willReturn(HttpResponses::randomArray());
        $this->getLicense()->shouldBeArray();
    }

    public function it_should_throw_on_get_license_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/License')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetLicense();

        $client->get('Service/License')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetLicense();
    }

    public function it_should_get_hardwareid_from_service(HttpClient $client)
    {
        $client->get('Service/HardwareId')->shouldBeCalled()->willReturn(HttpResponses::randomString());
        $this->getHardwareId()->shouldBeString();
    }

    public function it_should_throw_on_get_hardwareid_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/HardwareId')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetHardwareId();

        $client->get('Service/HardwareId')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetHardwareId();
    }

    public function it_should_get_settings_from_service(HttpClient $client)
    {
        $client->get('Service/Settings')->shouldBeCalled()->willReturn(HttpResponses::randomArray());
        $this->getSettings()->shouldBeArray();
    }

    public function it_should_throw_on_get_settings_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Settings')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetSettings();

        $client->get('Service/Settings')->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetSettings();
    }
}
