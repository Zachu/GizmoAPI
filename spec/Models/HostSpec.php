<?php namespace spec\Pisa\GizmoAPI\Models;

use PhpSpec\ObjectBehavior;
use Pisa\GizmoAPI\Contracts\HttpClient;
use spec\Pisa\GizmoAPI\HttpResponses;

class HostSpec extends ObjectBehavior
{
    protected static $id = 1;

    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client, ['Id' => self::$id]);
    }

    //
    // Construct
    //

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Models\Host');
    }

    //
    // Save
    //

    public function it_should_throw_on_create(HttpClient $client)
    {
        $this->beConstructedWith($client, []);

        $this->shouldThrow('\Exception')->duringSave();
    }

    public function it_should_throw_on_update()
    {
        $this->HostName = 'Test';

        $this->shouldThrow('\Exception')->duringSave();
    }

    //
    // Delete
    //

    public function it_should_throw_on_delete()
    {
        $this->shouldThrow('\Exception')->duringDelete();
    }

    //
    // Get processes
    //

    public function it_should_get_processes(HttpClient $client)
    {
        $client->get("Host/GetProcesses", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $this->getProcesses()->shouldBeArray();
        $this->getProcesses()->shouldHaveCount(0);

        $client->get("Host/GetProcesses", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            'process1',
            'process2',
        ]));

        $this->getProcesses()->shouldBeArray();
        $this->getProcesses()->shouldHaveCount(2);
    }

    public function it_should_throw_on_get_prorcesses_if_model_doesnt_exist(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringGetProcesses();
    }

    public function it_should_throw_on_get_processes_if_got_unexpected_response(HttpClient $client)
    {
        $client->get("Host/GetProcesses", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetProcesses();
    }

    //
    // Get a process
    //

    public function it_should_get_a_process(HttpClient $client)
    {
        $pid = 1;
        $client->get("Host/GetProcess", [
            'hostId'    => $this->getPrimaryKeyValue(),
            'processId' => $pid,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $this->getProcess($pid)->shouldBeArray();
        $this->getProcess($pid)->shouldHaveCount(0);
    }

    public function it_should_throw_on_get_process_if_model_doesnt_exist(HttpClient $client)
    {
        $pid = 1;
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringGetProcess($pid);
    }

    public function it_should_throw_on_get_process_if_got_unexpected_response(HttpClient $client)
    {
        $pid = 1;
        $client->get("Host/GetProcess", [
            'hostId'    => $this->getPrimaryKeyValue(),
            'processId' => $pid,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetProcess($pid);
    }

    //
    // Get processes by name
    //

    public function it_should_get_processes_by_name(HttpClient $client)
    {
        $pname = 'process';
        $client->get("Host/GetProcesses", [
            'hostId'      => $this->getPrimaryKeyValue(),
            'processName' => $pname,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $this->getProcessesByName($pname)->shouldBeArray();
        $this->getProcessesByName($pname)->shouldHaveCount(0);

        $client->get("Host/GetProcesses", [
            'hostId'      => $this->getPrimaryKeyValue(),
            'processName' => $pname,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            'process1',
            'process2',
        ]));
        $this->getProcessesByName($pname)->shouldBeArray();
        $this->getProcessesByName($pname)->shouldHaveCount(2);
    }

    public function it_should_throw_on_get_processes_by_name_if_given_other_than_string(HttpClient $client)
    {
        $pname = ['process'];

        $this->shouldThrow('\Exception')->duringGetProcessesByName($pname);
    }

    public function it_should_throw_on_get_processes_by_name_if_model_doesnt_exist(HttpClient $client)
    {
        $pname = 'process';
        $this->beConstructedWith($client, []);

        $this->shouldThrow('\Exception')->duringGetProcessesByName($pname);
    }

    public function it_should_throw_on_get_processes_by_name_if_got_unexpected_response(HttpClient $client)
    {
        $pname = 'process';
        $client->get("Host/GetProcesses", [
            'hostId'      => $this->getPrimaryKeyValue(),
            'processName' => $pname,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringGetProcessesByName($pname);
    }

    //
    // Create process
    //

    public function it_should_create_processes(HttpClient $client)
    {
        $startInfo = [
            'FileName' => 'foo',
        ];

        $client->post("Host/CreateProcess", array_merge($startInfo, [
            'hostId' => $this->getPrimaryKeyValue(),
        ]))->shouldBeCalled()->willReturn(HttpResponses::one());

        $this->CreateProcess($startInfo)->shouldBeInteger();
    }

    public function it_should_return_false_on_create_process_when_response_500(HttpClient $client)
    {
        $startInfo = [
            'FileName' => 'foo',
        ];

        $client->post("Host/CreateProcess", array_merge($startInfo, [
            'hostId' => $this->getPrimaryKeyValue(),
        ]))->shouldBeCalled()->willReturn(HttpResponses::internalServerError());

        $this->CreateProcess($startInfo)->shouldBe(false);
    }

    public function it_should_throw_on_create_process_if_model_doesnt_exist(HttpClient $client)
    {
        $startInfo = [
            'FileName' => 'foo',
        ];
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringCreateProcess($startInfo);
    }

    public function it_should_throw_on_create_process_on_unexpected_response(HttpClient $client)
    {
        $startInfo = [
            'FileName' => 'foo',
        ];

        $client->post("Host/CreateProcess", array_merge($startInfo, [
            'hostId' => $this->getPrimaryKeyValue(),
        ]))->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringCreateProcess($startInfo);
    }

    public function it_should_throw_on_create_process_when_not_given_an_array()
    {
        $this->shouldThrow('\Exception')->duringCreateProcess("FileName.exe");
    }

    //
    // Terminate process
    //

    public function it_should_terminate_processes(HttpClient $client)
    {
        $killInfo = [
            'FileName' => 'foo',
        ];

        $client->post("Host/TerminateProcess", array_merge($killInfo, [
            'hostId' => $this->getPrimaryKeyValue(),
        ]))->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->terminateProcess($killInfo)->shouldBe(true);
    }

    public function it_should_throw_on_terminate_process_if_model_doesnt_exist(HttpClient $client)
    {
        $killInfo = [
            'FileName' => 'foo',
        ];
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringTerminateProcess($killInfo);
    }

    public function it_should_throw_on_terminate_process_on_unexpected_response(HttpClient $client)
    {
        $killInfo = [
            'FileName' => 'foo',
        ];

        $client->post("Host/TerminateProcess", array_merge($killInfo, [
            'hostId' => $this->getPrimaryKeyValue(),
        ]))->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringTerminateProcess($killInfo);
    }

    public function it_should_throw_on_terminate_process_when_not_given_an_array()
    {
        $this->shouldThrow('\Exception')->duringTerminateProcess("FileName.exe");
    }

    //
    // Get last user login time
    //

    public function it_should_get_last_user_login_time(HttpClient $client)
    {
        $client->get("Host/GetLastUserLogin", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::time());

        $this->getLastUserLoginTime()->shouldBeInteger();
    }

    public function it_should_throw_on_get_last_user_login_time_if_model_doesnt_exist(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringGetLastUserLoginTime();
    }

    public function it_should_throw_on_get_last_user_login_time_if_got_unexpected_response(HttpClient $client)
    {
        $client->get("Host/GetLastUserLogin", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringGetLastUserLoginTime();
    }

    //
    // Get last user logout time
    //

    public function it_should_get_last_user_logout_time(HttpClient $client)
    {
        $client->get("Host/GetLastUserLogout", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::time());

        $this->getLastUserLogoutTime()->shouldBeInteger();
    }

    public function it_should_throw_on_get_last_user_logout_time_if_model_doesnt_exist(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringGetLastUserLogoutTime();
    }

    public function it_should_throw_on_get_last_user_logout_time_if_got_unexpected_response(HttpClient $client)
    {
        $client->get("Host/GetLastUserLogout", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringGetLastUserLogoutTime();
    }

    //
    // Logout user
    //

    public function it_should_logout_user(HttpClient $client)
    {
        $client->post("Host/UserLogout", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->userLogout()->shouldBe(true);
    }

    public function it_should_throw_on_logout_user_if_model_doesnt_exists(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringUserLogout();
    }

    public function it_should_throw_on_logout_user_if_got_unexpected_response(HttpClient $client)
    {
        $client->post("Host/UserLogout", [
            'hostId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringUserLogout();
    }

    //
    // Notify UI
    //

    public function it_should_notify_ui(HttpClient $client)
    {
        $message    = 'Test';
        $parameters = [];
        $client->post("Host/UINotify", array_merge($this->getDefaultNotifyParameters()->getWrappedObject(), $parameters, [
            'hostId'  => $this->getPrimaryKeyValue(),
            'message' => $message,
        ]))->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->UINotify($message)->shouldBe(true);
    }

    public function it_should_throw_on_ui_notify_if_model_doesnt_exists(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringUINotify('Test');
    }

    public function it_should_throw_on_ui_notify_if_got_unexpected_response(HttpClient $client)
    {
        $message    = 'Test';
        $parameters = [];

        $client->post("Host/UINotify", array_merge($this->getDefaultNotifyParameters()->getWrappedObject(), $parameters, [
            'hostId'  => $this->getPrimaryKeyValue(),
            'message' => $message,
        ]))->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringUINotify($message);
    }

    //
    // Set lock state
    //

    public function it_should_set_lock_state_to_false(HttpClient $client)
    {
        $client->post("Host/SetLockState", [
            'hostId' => $this->getPrimaryKeyValue(),
            'locked' => "false",
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->IsLocked = false;
        $this->save();
        $this->IsLocked->shouldBe(false);
    }

    public function it_should_set_lock_state_to_true(HttpClient $client)
    {
        $client->post("Host/SetLockState", [
            'hostId' => $this->getPrimaryKeyValue(),
            'locked' => "true",
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->IsLocked = true;
        $this->save();
        $this->IsLocked->shouldBe(true);
    }

    public function it_should_throw_on_lock_state_when_setting_other_than_boolean()
    {
        $this->shouldThrow('\Exception')->duringSetLockState("Invalid");
    }

    public function it_should_throw_on_lock_state_if_got_unexpected_response(HttpClient $client)
    {
        $client->post("Host/SetLockState", [
            'hostId' => $this->getPrimaryKeyValue(),
            'locked' => "true",
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringSetLockState(true);
    }

    public function it_should_throw_on_lock_state_if_model_doesnt_exists(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringSetLockState(true);
    }

    //
    // Set security state
    //

    public function it_should_set_security_state_to_false(HttpClient $client)
    {
        $client->post("Host/SetSecurityState", [
            'hostId'  => $this->getPrimaryKeyValue(),
            'enabled' => "false",
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->IsSecurityEnabled = false;
        $this->save();
        $this->IsSecurityEnabled->shouldBe(false);
    }

    public function it_should_set_security_state_to_true(HttpClient $client)
    {
        $client->post("Host/SetSecurityState", [
            'hostId'  => $this->getPrimaryKeyValue(),
            'enabled' => "true",
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->IsSecurityEnabled = true;
        $this->save();
        $this->IsSecurityEnabled->shouldBe(true);
    }

    public function it_should_throw_on_security_state_when_setting_other_than_boolean()
    {
        $this->shouldThrow('\Exception')->duringSetSecurityState("Invalid");
    }

    public function it_should_throw_on_security_state_if_got_unexpected_response(HttpClient $client)
    {
        $client->post("Host/SetSecurityState", [
            'hostId'  => $this->getPrimaryKeyValue(),
            'enabled' => "true",
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringSetSecurityState(true);
    }

    public function it_should_throw_security_state_if_model_doesnt_exists(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringSetSecurityState(true);
    }

    //
    // Set order state
    //

    public function it_should_set_out_of_order_to_false(HttpClient $client)
    {
        $client->post("Host/SetOrderState", [
            'hostId'  => $this->getPrimaryKeyValue(),
            'inOrder' => "true",
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->IsOutOfOrder = false;
        $this->save();
        $this->IsOutOfOrder->shouldBe(false);
    }

    public function it_should_set_out_of_order_to_true(HttpClient $client)
    {
        $client->post("Host/SetOrderState", [
            'hostId'  => $this->getPrimaryKeyValue(),
            'inOrder' => "false",
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->IsOutOfOrder = true;
        $this->save();
        $this->IsOutOfOrder->shouldBe(true);
    }

    public function it_should_throw_on_out_of_order_when_setting_other_than_boolean()
    {
        $this->shouldThrow('\Exception')->duringSetOrderState("Invalid");
    }

    public function it_should_throw_on_out_of_order_if_got_unexpected_response(HttpClient $client)
    {
        $client->post("Host/SetOrderState", [
            'hostId'  => $this->getPrimaryKeyValue(),
            'inOrder' => "false",
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringSetOrderState(true);
    }

    public function it_should_throw_on_out_of_order_if_model_doesnt_exists(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringSetOrderState(true);
    }

    //
    // Get free state
    //

    public function it_should_get_free_state(HttpClient $client)
    {
        $client->get('Sessions/GetActive')->shouldBeCalled()->willReturn(HttpResponses::content([
            ['HostId' => $this->getPrimaryKeyValue()->getWrappedObject()],
        ]));
        $this->isFree()->shouldReturn(false);

        $client->get('Sessions/GetActive')->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->isFree()->shouldReturn(true);

    }

    public function it_should_throw_on_get_free_state_if_model_doesnt_exist(HttpClient $client)
    {
        $this->beConstructedWith($client, []);
        $this->shouldThrow('\Exception')->duringIsFree();
    }

    public function it_should_throw_on_get_free_state_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Sessions/GetActive')->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringIsFree();
    }
}
