<?php namespace spec\Pisa\GizmoAPI\Repositories;

use PhpSpec\ObjectBehavior;
use spec\Pisa\GizmoAPI\Helper;
use Pisa\GizmoAPI\Contracts\HttpClient;

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
        $client->get('Service/Time')
            ->shouldBeCalled()->willReturn(Helper::timeResponse());
        $this->getTime()->shouldBeInteger();
    }

    public function it_should_throw_on_get_time_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Time')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetTime();

        $client->get('Service/Time')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetTime();
    }

    public function it_should_stop_service(HttpClient $client)
    {
        $client->get('Service/Stop')
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());
        $this->stop();
    }

    public function it_should_throw_on_stop_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Stop')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringStop();

        $client->get('Service/Stop')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringStop();
    }

    public function it_should_restart_service(HttpClient $client)
    {
        $client->get('Service/Restart')
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());
        $this->restart();
    }

    public function it_should_throw_on_restart_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Restart')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringRestart();

        $client->get('Service/Restart')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringRestart();
    }

    public function it_should_get_status_from_service(HttpClient $client)
    {
        $client->get('Service/Status')
            ->shouldBeCalled()->willReturn(Helper::randomArrayResponse());
        $this->getStatus()->shouldBeArray();
    }

    public function it_should_throw_on_get_status_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Status')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetStatus();

        $client->get('Service/Status')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetStatus();
    }

    public function it_should_get_version_from_service(HttpClient $client)
    {
        $client->get('Service/Version')
            ->shouldBeCalled()->willReturn(Helper::randomStringResponse());
        $this->getVersion()->shouldBeString();
    }

    public function it_should_throw_on_get_version_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Version')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetVersion();

        $client->get('Service/Version')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetVersion();
    }

    public function it_should_get_module_from_service(HttpClient $client)
    {
        $client->get('Service/Module')
            ->shouldBeCalled()->willReturn(Helper::randomArrayResponse());
        $this->getModule()->shouldBeArray();
    }

    public function it_should_throw_on_get_module_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Module')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetModule();

        $client->get('Service/Module')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetModule();
    }

    public function it_should_get_license_from_service(HttpClient $client)
    {
        $client->get('Service/License')
            ->shouldBeCalled()->willReturn(Helper::randomArrayResponse());
        $this->getLicense()->shouldBeArray();
    }

    public function it_should_throw_on_get_license_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/License')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetLicense();

        $client->get('Service/License')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetLicense();
    }

    public function it_should_get_hardwareid_from_service(HttpClient $client)
    {
        $client->get('Service/HardwareId')
            ->shouldBeCalled()->willReturn(Helper::randomStringResponse());
        $this->getHardwareId()->shouldBeString();
    }

    public function it_should_throw_on_get_hardwareid_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/HardwareId')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetHardwareId();

        $client->get('Service/HardwareId')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetHardwareId();
    }

    public function it_should_get_settings_from_service(HttpClient $client)
    {
        $client->get('Service/Settings')
            ->shouldBeCalled()->willReturn(Helper::randomArrayResponse());
        $this->getSettings()->shouldBeArray();
    }

    public function it_should_throw_on_get_settings_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Service/Settings')
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetSettings();

        $client->get('Service/Settings')
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetSettings();
    }
}
