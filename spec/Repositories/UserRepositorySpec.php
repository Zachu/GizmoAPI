<?php namespace spec\Pisa\Api\Gizmo\Repositories;

use PhpSpec\ObjectBehavior;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter;
use Pisa\Api\Gizmo\Contracts\Container;
use Pisa\Api\Gizmo\Models\User;
use spec\Pisa\Api\Gizmo\HttpResponses;

class UserRepositorySpec extends ObjectBehavior
{
    protected static $skip    = 2;
    protected static $top     = 1;
    protected static $orderby = 'Number';

    public function Let(HttpClientAdapter $client, Container $ioc)
    {
        $this->beConstructedWith($ioc, $client);
        $this->shouldHaveType('Pisa\Api\Gizmo\Repositories\UserRepository');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Repositories\UserRepository');
    }

    public function it_should_get_all_users(HttpClientAdapter $client, Container $ioc, User $user)
    {
        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);

        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldContain($user);
    }

    public function it_should_return_empty_list_on_get_all_users(HttpClientAdapter $client, Container $ioc)
    {
        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();

        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClientAdapter $client, Container $ioc)
    {
        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);

        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_users_by_parameters(HttpClientAdapter $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled();

        $result = $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
        $result->shouldBeArray();
        $result->shouldHaveCount(2);
    }

    public function it_should_find_users_by_case_sensitive_parameters(HttpClientAdapter $client, Container $ioc)
    {
        $caseSensitive = true;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled();

        $result = $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
        $result->shouldBeArray();
        $result->shouldHaveCount(2);
    }

    public function it_should_throw_on_find_users_by_parameters_if_got_unexpected_response(HttpClientAdapter $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
    }

    public function it_should_return_empty_list_on_find_users_by_parameters(HttpClientAdapter $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();

        $result = $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
        $result->shouldBeArray();
        $result->shouldHaveCount(0);
    }

    public function it_should_find_one_user_by_parameters(HttpClientAdapter $client, Container $ioc, User $user)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);
        $result = $this->findOneBy($criteria, $caseSensitive);
        $result->shouldBe($user);
    }

    public function it_should_find_one_user_by_case_sensitive_parameters(HttpClientAdapter $client, Container $ioc, User $user)
    {
        $caseSensitive = true;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);
        $result = $this->findOneBy($criteria, $caseSensitive);
        $result->shouldBe($user);
    }

    public function it_should_throw_on_find_one_user_by_parameters_if_got_unexpected_response(HttpClientAdapter $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindOneBy($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindOneBy($criteria, $caseSensitive);
    }

    public function it_should_return_false_when_no_user_is_found_by_parameters(HttpClientAdapter $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->findOneBy($criteria, $caseSensitive)->shouldBe(false);
    }

    public function it_should_get_user(HttpClientAdapter $client, Container $ioc, User $user)
    {
        $id = 2;
        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => $id],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);
        $this->get($id)->shouldBe($user);
    }

    public function it_should_return_false_if_no_user_is_found_on_get(HttpClientAdapter $client, Container $ioc)
    {
        $id = 2;

        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->get($id)->shouldBe(false);
    }

    public function it_should_throw_on_get_user_if_got_unexpected_response(HttpClientAdapter $client, Container $ioc)
    {
        $id = 2;

        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringGet($id);

        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringGet($id);
    }

    public function it_should_throw_on_get_user_if_parameter_is_not_integer(HttpClientAdapter $client)
    {
        $this->shouldThrow('\Exception')->duringGet('foo');
    }

    public function it_should_check_if_user_exists(HttpClientAdapter $client)
    {
        $id = 2;
        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->has($id)->shouldBe(true);

        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->has($id)->shouldBe(false);
    }

    public function it_should_throw_on_has_user_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $id = 2;

        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->shouldThrow('\Exception')->duringHas($id);

        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringHas($id);
    }
    public function it_should_check_if_username_exists(HttpClientAdapter $client)
    {
        $userName = 'Tester';
        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->hasUserName($userName)->shouldReturn(true);

        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->hasUserName($userName)->shouldReturn(false);
    }

    public function it_should_throw_on_has_username_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $userName = 'Tester';
        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->shouldThrow('\Exception')->duringHasUserName($userName);

        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringHasUserName($userName);
    }
    public function it_should_check_if_email_exists(HttpClientAdapter $client)
    {
        $email = 'test@example.com';
        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->hasUserEmail($email)->shouldReturn(true);

        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->hasUserEmail($email)->shouldReturn(false);
    }

    public function it_should_throw_on_has_email_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $email = 'test@example.com';

        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->shouldThrow('\Exception')->duringHasUserEmail($email);

        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringHasUserEmail($email);
    }

    public function it_should_check_if_loginname_exists(HttpClientAdapter $client)
    {
        $loginName = 'test@example.com';
        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->hasLoginName($loginName)->shouldReturn(true);

        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(HttpResponses::false());
        $this->hasLoginName($loginName)->shouldReturn(false);

    }

    public function it_should_throw_on_has_loginname_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $loginName = 'test@example.com';

        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->shouldThrow('\Exception')->duringhasLoginName($loginName);

        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringhasLoginName($loginName);

    }
}
