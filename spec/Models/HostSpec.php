<?php

namespace spec\Pisa\Api\Gizmo\Models;

use GuzzleHttp\Psr7\Response as HttpResponse;
use PhpSpec\ObjectBehavior;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Adapters\HttpResponseAdapter as HttpResponseAdapter;

class HostSpec extends ObjectBehavior
{
    protected static $id = 1;
    protected static $httpEmpty;
    protected static $httpInt;
    protected static $httpNoContent;
    protected static $httpTimestamp;

    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client, ['Id' => self::$id]);

        self::$httpEmpty = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode([])
        ));

        self::$httpInt = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(123456)
        ));

        self::$httpNoContent = new HttpResponseAdapter(new HttpResponse(204));

        self::$httpTimestamp = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(date('c'))
        ));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Models\Host');
    }

    public function it_should_throw_on_cud()
    {
        $this->shouldThrow('\Exception')->duringCreate();
        $this->shouldThrow('\Exception')->duringUpdate();
        $this->shouldThrow('\Exception')->duringDelete();
        $this->shouldThrow('\Exception')->duringSave();
    }

    public function it_should_get_processes(HttpClient $client)
    {
        $client->get("Host/GetProcesses", ['hostId' => self::$id])->shouldBeCalled()->willReturn(self::$httpEmpty);
        $this->getProcesses()->shouldBeArray();
        $this->getProcesses()->shouldHaveCount(0);

    }

    public function it_should_get_a_process(HttpClient $client)
    {
        $pid = 1;
        $client->get("Host/GetProcess", ['hostId' => self::$id, 'processId' => $pid])->shouldBeCalled()->willReturn(self::$httpEmpty);
        $this->getProcess($pid)->shouldBeArray();
        $this->getProcess($pid)->shouldHaveCount(0);
    }

    public function it_should_get_processes_by_name(HttpClient $client)
    {
        $pname = 'process';
        $client->get("Host/GetProcesses", ['hostId' => self::$id, 'processName' => $pname])->shouldBeCalled()->willReturn(self::$httpEmpty);
        $this->getProcessesByName($pname)->shouldBeArray();
        $this->getProcessesByName($pname)->shouldHaveCount(0);
    }

    public function it_should_create_processes(HttpClient $client)
    {
        $startInfo = [
            'FileName' => 'foo',
        ];

        $client->post("Host/CreateProcess", array_merge($startInfo, ['hostId' => self::$id]))->shouldBeCalled()->willReturn(self::$httpInt);
        $this->createProcess($startInfo)->shouldBeInteger();
    }

    public function it_should_terminate_processes(HttpClient $client)
    {
        $killInfo = [
            'FileName' => 'foo',
        ];

        $client->post("Host/TerminateProcess", array_merge($killInfo, ['hostId' => self::$id]))->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->terminateProcess($killInfo)->shouldBe(true);
    }

    public function it_should_get_last_user_login_time(HttpClient $client)
    {
        $client->get("Host/GetLastUserLogin", ['hostId' => self::$id])->shouldBeCalled()->willReturn(self::$httpTimestamp);
        $this->getLastUserLoginTime()->shouldBeInteger();
    }

    public function it_should_get_last_user_logout_time(HttpClient $client)
    {
        $client->get("Host/GetLastUserLogout", ['hostId' => self::$id])->shouldBeCalled()->willReturn(self::$httpTimestamp);
        $this->getLastUserLogoutTime()->shouldBeInteger();
    }

    public function it_should_logout_users(HttpClient $client)
    {
        $client->post("Host/UserLogout", ['hostId' => self::$id])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->userLogout()->shouldBe(true);
    }

    public function it_should_notify_ui(HttpClient $client)
    {
        $message = 'Test';
        $client->post("Host/UINotify", ['hostId' => self::$id, 'message' => $message, 'parameters' => ''])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->UINotify($message)->shouldBe(true);
    }

    public function it_should_set_lock_state(HttpClient $client)
    {
        $client->post("Host/SetLockState", ['hostId' => self::$id, 'locked' => "true"])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->setLockState(true)->shouldBe(true);

        $client->post("Host/SetLockState", ['hostId' => self::$id, 'locked' => "false"])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->setLockState(false)->shouldBe(true);
    }

    public function it_should_set_security_state(HttpClient $client)
    {
        $client->post("Host/SetSecurityState", ['hostId' => self::$id, 'enabled' => "true"])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->setSecurityState(true)->shouldBe(true);

        $client->post("Host/SetSecurityState", ['hostId' => self::$id, 'enabled' => "false"])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->setSecurityState(false)->shouldBe(true);
    }

    public function it_should_set_order_state(HttpClient $client)
    {
        $client->post("Host/SetOrderState", ['hostId' => self::$id, 'inOrder' => "true"])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->setOrderState(true)->shouldBe(true);

        $client->post("Host/SetOrderState", ['hostId' => self::$id, 'inOrder' => "false"])->shouldBeCalled()->willReturn(self::$httpNoContent);
        $this->setOrderState(false)->shouldBe(true);
    }

    public function is_should_get_free_state(HttpClient $client)
    {

    }
}
