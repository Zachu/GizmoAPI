<?php namespace spec\Pisa\Api\Gizmo\Models;

use PhpSpec\ObjectBehavior;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Models\HostInterface;
use Pisa\Api\Gizmo\Repositories\UserRepositoryInterface;
use spec\Pisa\Api\Gizmo\HttpResponses;

class UserSpec extends ObjectBehavior
{
    protected static $id = 1;

    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client, ['Id' => self::$id, 'UserName' => 'Teddy', 'FirstName' => 'Tedd', 'LastName' => 'Tester']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Models\User');
    }

    public function it_should_create_new_user_on_save(HttpClient $client)
    {
        $this->load(['Id' => null], true); //Lets fake that the model isn't created yet

        $client->post('Users/Create', $this->getAttributes())->shouldBeCalled()->willReturn(null);
        $this->save();

        //@todo test exceptions
    }
    public function it_should_update_user_information_on_save(HttpClient $client)
    {
        $this->FirstName = 'Todd';
        $client->post('Users/Update', $this->getAttributes())->shouldBeCalled()->willReturn(null);
        $this->save();

        //@todo test exceptions
    }
    public function it_should_delete_user(HttpClient $client)
    {
        $client->get('Users/GetLoginState', [
            'userId' => self::$id,
        ])->shouldBeCalled()->willReturn(HttpResponses::false());

        $client->delete('Users/Delete', ['userId' => self::$id])->shouldBeCalled()->willReturn(null);
        $this->delete();

        //@todo test exceptions
    }
    public function it_should_handle_rename_on_save(HttpClient $client, UserRepositoryInterface $repository)
    {
        //Username is available should be ok
        $this->UserName = 'Available';
        $repository->hasUserName($this->UserName)->shouldBeCalled()->willReturn(false);
        $client->post('Users/Rename', [
            'userId' => self::$id,
            'newUserName' => $this->UserName,
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $client->post('Users/Update', $this->getAttributes())->shouldBeCalled()->willReturn(null);
        $this->save($repository);

        //Username is taken should throw exception
        $this->UserName = 'Taken';
        $repository->hasUserName($this->UserName)->shouldBeCalled()->willReturn(true);
        $this->shouldThrow('\Exception')->duringSave($repository);

        //@todo Invalid response exception
    }
    public function it_should_handle_usergroup_change_on_save(HttpClient $client)
    {
        $this->GroupId = 1;

        $client->post('Users/SetUserGroup', [
            'userId' => $this->getPrimaryKey(),
            'newUserGroup' => $this->GroupId,
        ])->shouldBeCalled()->willReturn(null);
        $client->post('Users/Update', $this->getAttributes())->shouldBeCalled()->willReturn(null);

        $this->save();

        //@todo test exceptions
    }

    public function it_should_handle_email_on_save(HttpClient $client, UserRepositoryInterface $repository)
    {
        //Email is not taken should be ok
        $this->Email = 'free@example.com';
        $repository->hasUserEmail($this->Email)->shouldBeCalled()->willReturn(false);
        $client->post('Users/SetUserEmail', [
            'userId' => $this->getPrimaryKeyValue(),
            'newEmail' => $this->Email,
        ])->shouldBeCalled()->willReturn(null);
        $client->post('Users/Update', $this->getAttributes())->shouldBeCalled()->willReturn(null);
        $this->save($repository);

        //Email is taken should throw exception
        $this->Email = 'taken@example.com';
        $repository->hasUserEmail($this->Email)->shouldBeCalled()->willReturn(true);
        $this->shouldThrow('\Exception')->duringSave($repository);

        //@todo Invalid response exception
    }
    public function it_should_get_logged_in_host_id(HttpClient $client)
    {
        //Valid response, should return the HostId
        $client->get('Users/GetLoggedInHost', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::one());
        $this->getLoggedInHostId()->shouldReturn(self::$id);

        //Zero-response (meaning no host) should return false
        $client->get('Users/GetLoggedInHost', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::zero());
        $this->getLoggedInHostId()->shouldReturn(false);

        //Invalid response should throw exception
        $client->get('Users/GetLoggedInHost', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->shouldThrow('\Exception')->duringGetLoggedInHostId();

        //Should throw if the model doesn't exist
        $this->Id = null;
        $this->shouldThrow('\Exception')->duringGetLoggedInHostId();
    }
    public function it_should_check_if_user_is_logged_in(HttpClient $client)
    {
        //Valid response true, should return true
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->isLoggedIn()->shouldReturn(true);

        //Valid response false, should return false
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->isLoggedIn()->shouldReturn(false);

        //Invalid response should throw exception
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::zero());
        $this->shouldThrow('\Exception')->duringIsLoggedIn();

        //Should throw if the model doesn't exist
        $this->Id = null;
        $this->shouldThrow('\Exception')->duringIsLoggedIn();
    }
    public function it_should_get_last_login_time(HttpClient $client)
    {
        //Valid response
        $client->get('Users/GetLastUserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::time());
        $this->lastLoginTime()->shouldBeInteger();

        //Invalid response should throw exception
        $client->get('Users/GetLastUserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->shouldThrow('\Exception')->duringLastLoginTime();

        //Should throw if the model doesn't exist
        $this->Id = null;
        $this->shouldThrow('\Exception')->duringLastLoginTime();

    }
    public function it_should_get_last_logout_time(HttpClient $client)
    {
        //Valid response
        $client->get('Users/GetLastUserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::time());
        $this->lastLogoutTime()->shouldBeInteger();

        //Invalid response should throw exception
        $client->get('Users/GetLastUserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->shouldThrow('\Exception')->duringLastLogoutTime();

        //Should throw if the model doesn't exist
        $this->Id = null;
        $this->shouldThrow('\Exception')->duringLastLogoutTime();
    }
    public function it_should_login_user_to_host(HttpClient $client, HostInterface $host)
    {
        //Valid login
        $host->isFree()->shouldBeCalled()->willReturn(true);
        $host->getPrimaryKeyValue()->shouldBeCalled()->willReturn(1);
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::false());

        $client->post('Users/UserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
            'hostId' => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->login($host);

        //Host is not free, should throw exception
        $host->isFree()->shouldBeCalled()->willReturn(false);
        $host->getPrimaryKeyValue()->shouldBeCalled()->willReturn(1);
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->shouldThrow('\Exception')->duringLogin($host);

        //User is already logged in, should throw exception
        $host->isFree()->shouldBeCalled()->willReturn(true);
        $host->getPrimaryKeyValue()->shouldBeCalled()->willReturn(1);
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringLogin($host);

        //Should throw if the model doesn't exist
        $this->Id = null;
        $this->shouldThrow('\Exception')->duringLogin($host);
    }
    public function it_should_logout_user_from_host(HttpClient $client)
    {
        //Valid logout
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $client->post('Users/UserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->logout();

        //Should throw when getting a weird response
        $client->post('Users/UserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringLogout();

        //User isn't logged in
        $client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->shouldThrow('\Exception')->duringLogout();

        //Should throw if the model doesn't exist
        $this->Id = null;
        $this->shouldThrow('\Exception')->duringLogout();

    }
/*    public function it_should_rename_user(HttpClient $client)
{
}
public function it_should_set_email(HttpClient $client)
{
}
public function it_should_set_password(HttpClient $client)
{
}
public function it_should_set_user_group(HttpClient $client)
{
}*/
}
