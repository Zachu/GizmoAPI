<?php namespace spec\Pisa\GizmoAPI\Repositories;

use PhpSpec\ObjectBehavior;
use Pisa\GizmoAPI\Contracts\Container;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Models\User;
use spec\Pisa\GizmoAPI\Helper;

class UserRepositorySpec extends ObjectBehavior
{
    protected static $skip    = 2;
    protected static $top     = 1;
    protected static $orderby = 'Number';

    public function Let(HttpClient $client, Container $ioc)
    {
        $this->beConstructedWith($ioc, $client);
        $this->shouldHaveType('Pisa\GizmoAPI\Repositories\UserRepository');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Repositories\UserRepository');
    }

    public function it_should_get_all_users(HttpClient $client, Container $ioc, User $user)
    {
        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);

        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldContain($user);
    }

    public function it_should_return_empty_list_on_get_all_users(HttpClient $client, Container $ioc)
    {
        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();

        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClient $client, Container $ioc)
    {
        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);

        $client->get('Users/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_users_by_parameters(HttpClient $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled();

        $result = $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
        $result->shouldBeArray();
        $result->shouldHaveCount(2);
    }

    public function it_should_find_users_by_case_sensitive_parameters(HttpClient $client, Container $ioc)
    {
        $caseSensitive = true;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled();

        $result = $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
        $result->shouldBeArray();
        $result->shouldHaveCount(2);
    }

    public function it_should_throw_on_find_users_by_parameters_if_got_unexpected_response(HttpClient $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
    }

    public function it_should_return_empty_list_on_find_users_by_parameters(HttpClient $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter'  => $filter,
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();

        $result = $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
        $result->shouldBeArray();
        $result->shouldHaveCount(0);
    }

    public function it_should_find_one_user_by_parameters(HttpClient $client, Container $ioc, User $user)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);
        $result = $this->findOneBy($criteria, $caseSensitive);
        $result->shouldBe($user);
    }

    public function it_should_find_one_user_by_case_sensitive_parameters(HttpClient $client, Container $ioc, User $user)
    {
        $caseSensitive = true;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([
            ['Id' => 2],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);
        $result = $this->findOneBy($criteria, $caseSensitive);
        $result->shouldBe($user);
    }

    public function it_should_throw_on_find_one_user_by_parameters_if_got_unexpected_response(HttpClient $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindOneBy($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringFindOneBy($criteria, $caseSensitive);
    }

    public function it_should_return_false_when_no_user_is_found_by_parameters(HttpClient $client, Container $ioc)
    {
        $caseSensitive = false;
        $criteria      = ['LastName' => 'Tester'];
        $filter        = $this->criteriaToFilter($criteria, $caseSensitive);

        $client->get('Users/Get', [
            '$filter' => $filter,
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->findOneBy($criteria, $caseSensitive)->shouldBe(false);
    }

    public function it_should_get_user(HttpClient $client, Container $ioc, User $user)
    {
        $id = 2;
        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([
            ['Id' => $id],
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($user);
        $this->get($id)->shouldBe($user);
    }

    public function it_should_return_false_if_no_user_is_found_on_get(HttpClient $client, Container $ioc)
    {
        $id = 2;

        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->get($id)->shouldBe(false);
    }

    public function it_should_throw_on_get_user_if_got_unexpected_response(HttpClient $client, Container $ioc)
    {
        $id = 2;

        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringGet($id);

        $client->get('Users/Get', [
            '$filter' => 'Id eq ' . $id,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Exception')->duringGet($id);
    }

    public function it_should_throw_on_get_user_if_parameter_is_not_integer(HttpClient $client)
    {
        $this->shouldThrow('\Exception')->duringGet('foo');
    }

    public function it_should_check_if_user_exists(HttpClient $client)
    {
        $id = 2;
        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->has($id)->shouldBe(true);

        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(Helper::falseResponse());
        $this->has($id)->shouldBe(false);
    }

    public function it_should_throw_on_has_user_if_got_unexpected_response(HttpClient $client)
    {
        $id = 2;

        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->shouldThrow('\Exception')->duringHas($id);

        $client->get('Users/UserExist', [
            'userId' => $id,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Exception')->duringHas($id);
    }
    public function it_should_check_if_username_exists(HttpClient $client)
    {
        $userName = 'Tester';
        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->hasUserName($userName)->shouldReturn(true);

        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(Helper::falseResponse());
        $this->hasUserName($userName)->shouldReturn(false);
    }

    public function it_should_throw_on_has_username_if_got_unexpected_response(HttpClient $client)
    {
        $userName = 'Tester';
        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->shouldThrow('\Exception')->duringHasUserName($userName);

        $client->get('Users/UserNameExist', [
            'userName' => $userName,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Exception')->duringHasUserName($userName);
    }
    public function it_should_check_if_email_exists(HttpClient $client)
    {
        $email = 'test@example.com';
        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->hasUserEmail($email)->shouldReturn(true);

        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(Helper::falseResponse());
        $this->hasUserEmail($email)->shouldReturn(false);
    }

    public function it_should_throw_on_has_email_if_got_unexpected_response(HttpClient $client)
    {
        $email = 'test@example.com';

        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->shouldThrow('\Exception')->duringHasUserEmail($email);

        $client->get('Users/UserEmailExist', [
            'userEmail' => $email,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Exception')->duringHasUserEmail($email);
    }

    public function it_should_check_if_loginname_exists(HttpClient $client)
    {
        $loginName = 'test@example.com';
        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->hasLoginName($loginName)->shouldReturn(true);

        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(Helper::falseResponse());
        $this->hasLoginName($loginName)->shouldReturn(false);

    }

    public function it_should_throw_on_has_loginname_if_got_unexpected_response(HttpClient $client)
    {
        $loginName = 'test@example.com';

        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->shouldThrow('\Exception')->duringhasLoginName($loginName);

        $client->get('Users/LoginNameExist', [
            'loginName' => $loginName,
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Exception')->duringhasLoginName($loginName);

    }
}
