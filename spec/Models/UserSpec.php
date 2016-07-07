<?php namespace spec\Pisa\GizmoAPI\Models;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use spec\Pisa\GizmoAPI\Helper;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Models\HostInterface;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Pisa\GizmoAPI\Repositories\UserRepositoryInterface;

class UserSpec extends ObjectBehavior
{
    protected static $user;

    public function let(HttpClient $client, Factory $factory, Validator $validator)
    {
        self::$user = Helper::fakeUser();
        $this->beConstructedWith($client, $factory, self::$user);
        $factory->make(Argument::any(), Argument::any())->willReturn($validator);
        $validator->fails()->willReturn(false);
    }

    //
    // Construct
    //

    public function it_is_initializable(HttpClient $client)
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Models\User');

        $this->Id->shouldBe(self::$user['Id']);
        $this->UserName->shouldBe(self::$user['UserName']);
        $this->FirstName->shouldBe(self::$user['FirstName']);
        $this->LastName->shouldBe(self::$user['LastName']);
    }

    //
    // Delete
    //

    public function it_should_delete_user(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::zeroResponse());

        $this->exists()->shouldBe(true);
        $client->delete('Users/Delete', ['userId' => $this->getPrimaryKeyValue()])
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->delete()->shouldReturn($this);
        $this->exists()->shouldBe(false);
    }

    public function it_should_throw_on_delete_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->willReturn(Helper::zeroResponse());

        $client->delete('Users/Delete', ['userId' => $this->getPrimaryKeyValue()])
            ->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringDelete();
    }

    public function it_should_logout_on_delete_if_user_is_logged_in(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->willReturn(Helper::oneResponse());

        $client->post('Users/UserLogout', ['userId' => $this->getPrimaryKeyValue()])
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());
        $client->delete('Users/Delete', ['userId' => $this->getPrimaryKeyValue()])
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->delete()->shouldReturn($this);
    }

    public function it_should_throw_on_delete_if_model_doesnt_exist(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser([
            'Id' => null,
        ]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringDelete();
    }

    //
    // Save
    //

    public function it_should_create_new_user_on_save(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $client->post('Users/Create', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_create_if_got_unexpected_response(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $client->post('Users/Create', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSave();
    }

    public function it_should_update_user_information_on_save(HttpClient $client)
    {
        $this->FirstName = 'Todd';
        $client->post('Users/Update', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_update_if_got_unexpected_response(HttpClient $client)
    {
        $this->FirstName = 'Todd';

        $client->post('Users/Update', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSave();
    }

    public function it_should_handle_rename_on_save(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newUserName = 'NewUserName';

        $this->UserName = $newUserName;
        $repository->hasUserName($newUserName)->shouldBeCalled();

        // Throws InternalException because response is not mocked
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\InternalException')
            ->duringSave($repository);
    }

    public function it_should_handle_usergroup_change_on_save(HttpClient $client)
    {
        $groupId = 99;

        $this->GroupId = $groupId;
        $client->post('Users/SetUserGroup', [
            'userId'       => $this->getPrimaryKeyValue(),
            'newUserGroup' => $groupId,
        ])->shouldBeCalled();

        // Throws InternalException because response is not mocked
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\InternalException')
            ->duringSave();
    }

    public function it_should_handle_email_on_save(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newEmail = 'test@example.com';

        $this->Email = $newEmail;
        $repository->hasUserEmail($newEmail)->shouldBeCalled();

        // Throws InternalException because response is not mocked
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\InternalException')
            ->duringSave($repository);
    }

    //
    // Get logged in host id
    //

    public function it_should_get_logged_in_host_id(HttpClient $client)
    {
        $client->get('Users/GetLoggedInHost', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::oneResponse());

        $this->getLoggedInHostId()->shouldReturn(1);
    }

    public function it_should_return_false_on_get_logged_in_host_if_not_logged_in(HttpClient $client)
    {
        $client->get('Users/GetLoggedInHost', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::zeroResponse());

        $this->getLoggedInHostId()->shouldReturn(false);
    }

    public function it_should_throw_on_get_logged_in_host_id_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Users/GetLoggedInHost', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::falseResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetLoggedInHostId();
    }

    public function it_should_throw_on_get_logged_in_host_id_if_model_doesnt_exist(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringGetLoggedInHostId();
    }

    //
    // Is logged in
    //

    public function it_should_check_if_user_is_logged_in(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::oneResponse());
        $this->isLoggedIn()->shouldReturn(true);

        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::zeroResponse());

        $this->isLoggedIn()->shouldReturn(false);
    }

    public function it_should_throw_on_is_logged_in_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::falseResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringIsLoggedIn();
    }

    public function it_should_throw_on_is_logged_in_if_model_doesnt_exist(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringIsLoggedIn();
    }

//
    // Get last login time
    //

    public function it_should_get_last_login_time(HttpClient $client)
    {
        $client->get('Users/GetLastUserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::timeResponse());

        $this->lastLoginTime()->shouldBeInteger();
    }

    public function it_should_throw_on_last_login_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Users/GetLastUserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringLastLoginTime();
    }

    public function it_should_throw_on_get_last_login_time_if_model_does_not_exists(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringLastLoginTime();
    }

    //
    // Get last logout time
    //

    public function it_should_get_last_logout_time(HttpClient $client)
    {
        $client->get('Users/GetLastUserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::timeResponse());

        $this->lastLogoutTime()->shouldBeInteger();
    }

    public function it_should_throw_on_get_last_logout_time_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Users/GetLastUserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringLastLogoutTime();
    }

    public function it_should_throw_on_get_last_logout_time_if_model_does_not_exist(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringLastLogoutTime();
    }

    //
    // Login
    //

    public function it_should_login_user_to_host(
        HttpClient $client,
        HostInterface $host
    ) {
        $host->isFree()->shouldBeCalled()->willReturn(true);
        $host->getPrimaryKeyValue()->shouldBeCalled()->willReturn(1);
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::zeroResponse());

        $client->post('Users/UserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
            'hostId' => 1,
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->login($host);
    }

    public function it_should_throw_on_login_if_host_is_not_free(
        HttpClient $client,
        HostInterface $host
    ) {
        $host->getPrimaryKeyValue()->willReturn(1);
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->willReturn(Helper::zeroResponse());

        $host->isFree()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringLogin($host);
    }

    public function it_should_throw_on_login_if_user_already_logged_in(
        HttpClient $client,
        HostInterface $host
    ) {
        $host->isFree()->willReturn(true);
        $host->getPrimaryKeyValue()->willReturn(1);

        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::oneResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringLogin($host);
    }

    public function it_should_throw_on_login_if_model_doesnt_exist(
        HttpClient $client,
        HostInterface $host,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));
        $host->isFree()->willReturn(true);
        $host->getPrimaryKeyValue()->willReturn(1);

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringLogin($host);
    }

    //
    // Logout
    //

    public function it_should_logout_user_from_host(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::oneResponse());

        $client->post('Users/UserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->logout();
    }

    public function it_should_throw_on_logout_when_got_unexpected_response(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::oneResponse());
        $client->post('Users/UserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringLogout();
    }

    public function it_should_throw_on_logout_if_user_not_logged_in(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::zeroResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringLogout();
    }

    public function it_should_throw_on_logout_if_model_doesnt_exist(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringLogout();
    }

    //
    // Rename
    //

    public function it_should_rename_user(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newUserName = 'NewName';

        $repository->hasUserName($newUserName)->shouldBeCalled()->willReturn(false);
        $client->post('Users/Rename', [
            'userId'      => $this->getPrimaryKeyValue(),
            'newUserName' => $newUserName,
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->rename($repository, $newUserName);
        $this->UserName->shouldBe($newUserName);
    }

    public function it_should_throw_on_rename_when_got_unexpected_response(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newUserName = 'NewName';
        $repository->hasUserName($newUserName)->shouldBeCalled()->willReturn(false);

        $client->post('Users/Rename', [
            'userId'      => $this->getPrimaryKeyValue(),
            'newUserName' => $newUserName,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringRename($repository, $newUserName);
        $this->UserName->shouldNotBe($newUserName);
    }

    public function it_should_throw_on_rename_if_username_is_taken(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newUserName = 'NewName';
        $repository->hasUserName($newUserName)->shouldBeCalled()->willReturn(true);

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\ValidationException')
            ->duringRename($repository, $newUserName);
        $this->UserName->shouldNotBe($newUserName);
    }

    public function it_should_throw_on_rename_if_model_doesnt_exist(
        HttpClient $client,
        UserRepositoryInterface $repository,
        Factory $factory
    ) {
        $newUserName = 'NewName';
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringRename($repository, $newUserName);
        $this->UserName->shouldNotBe($newUserName);
    }

    //
    // Set Email
    //

    public function it_should_set_email(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newEmail = 'test@example.com';
        $repository->hasUserEmail($newEmail)->shouldBeCalled()->willReturn(false);

        $client->post('Users/SetUserEmail', [
            'userId'   => $this->getPrimaryKeyValue(),
            'newEmail' => $newEmail,
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->setEmail($repository, $newEmail);
        $this->Email->shouldBe($newEmail);
    }

    public function it_should_throw_on_set_email_when_got_unexpected_reply(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newEmail = 'test@example.com';
        $repository->hasUserEmail($newEmail)->shouldBeCalled()->willReturn(false);

        $client->post('Users/SetUserEmail', [
            'userId'   => $this->getPrimaryKeyValue(),
            'newEmail' => $newEmail,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSetEmail($repository, $newEmail);
        $this->Email->shouldNotBe($newEmail);
    }

    public function it_should_throw_on_set_email_if_email_is_taken(
        HttpClient $client,
        UserRepositoryInterface $repository
    ) {
        $newEmail = 'test@example.com';
        $repository->hasUserEmail($newEmail)->shouldBeCalled()->willReturn(true);

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\ValidationException')->duringSetEmail($repository, $newEmail);
        $this->Email->shouldNotBe($newEmail);
    }

    public function it_should_throw_on_set_email_if_model_doesnt_exist(
        HttpClient $client,
        UserRepositoryInterface $repository,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));
        $newEmail = 'test@example.com';

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringSetEmail($repository, $newEmail);
        $this->Email->shouldNotBe($newEmail);
    }

    //
    // Set Password
    //

    public function it_should_set_password(HttpClient $client)
    {
        $newPassword = 'newPassword';
        $client->post('Users/SetUserPassword', [
            'userId'      => $this->getPrimaryKeyValue(),
            'newPassword' => $newPassword,
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->setPassword($newPassword);
    }

    public function it_should_throw_on_set_password_if_model_doesnt_exist(
        HttpClient $client,
        Factory $factory
    ) {
        $this->beConstructedWith($client, $factory, Helper::fakeUser(['Id' => null]));
        $newPassword = 'newPassword';

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringSetPassword($newPassword);
    }

    public function it_should_throw_on_set_password_when_got_unexpected_reply(HttpClient $client)
    {
        $newPassword = 'newPassword';

        $client->post('Users/SetUserPassword', [
            'userId'      => $this->getPrimaryKeyValue(),
            'newPassword' => $newPassword,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSetPassword($newPassword);
    }

    public function it_should_reset_password(HttpClient $client)
    {
        $client->post('Users/SetUserPassword', [
            'userId'      => $this->getPrimaryKeyValue(),
            'newPassword' => '',
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->resetPassword();
    }

    //
    // Set usergroup
    //

    public function it_should_set_user_group(HttpClient $client)
    {
        $newUserGroup = $this->getAttribute('GroupId')->getWrappedObject() + 1;

        $client->post('Users/SetUserGroup', [
            'userId'       => $this->getPrimaryKeyValue(),
            'newUserGroup' => $newUserGroup,
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->setUserGroup($newUserGroup);
        $this->GroupId->shouldBe($newUserGroup);
    }

    public function it_should_throw_on_set_user_group_if_model_doesnt_exist(
        HttpClient $client,
        Factory $factory
    ) {
        $fakeUser = Helper::fakeUser(['Id' => null]);
        $this->beConstructedWith($client, $factory, $fakeUser);
        $newUserGroup = $this->getAttribute('GroupId')->getWrappedObject() + 1;

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringSetUserGroup($newUserGroup);
        $this->GroupId->shouldNotBe($newUserGroup);
    }

    public function it_should_throw_on_set_user_group_when_got_unexpected_reply(HttpClient $client)
    {
        $newUserGroup = $this->getAttribute('GroupId')->getWrappedObject() + 1;

        $client->post('Users/SetUserGroup', [
            'userId'       => $this->getPrimaryKeyValue(),
            'newUserGroup' => $newUserGroup,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSetUserGroup($newUserGroup);
        $this->GroupId->shouldNotBe($newUserGroup);
    }
};
