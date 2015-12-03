<?php

namespace spec\Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Contracts\HttpClient;
use spec\Pisa\GizmoAPI\ApiTester;
use spec\Pisa\GizmoAPI\HttpResponses;

class SessionRepositorySpec extends ApiTester
{
    protected static $skip    = 2;
    protected static $top     = 1;
    protected static $orderby = 'CreationTime';

    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Repositories\SessionRepository');
    }

    public function it_should_get_all_sessions(HttpClient $client)
    {
        $options = ['$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeSession(),
            $this->fakeSession(),
        ]));
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClient $client)
    {
        $options = ['$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_active_sessions_by_criteria(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeSession(),
            $this->fakeSession(),
        ]));
        $this->findActiveBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->findActiveBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_find_active_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringFindActiveBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringFindActiveBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_active_session_infos_by_criteria(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeSession(),
            $this->fakeSession(),
        ]));
        $this->findActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->findActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_find_active_infos_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringFindActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringFindActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_sessions_by_criteria(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeSession(),
            $this->fakeSession(),
        ]));
        $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_find_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringFindBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringFindBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_one_session(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => 0, '$top' => 1];
        $session       = $this->fakeSession();

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $session,
        ]));
        $this->findOneBy($criteria, $caseSensitive)->shouldReturn($session);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->findOneBy($criteria, $caseSensitive)->shouldReturn(false);
    }

    public function it_should_throw_on_find_one_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => 0, '$top' => 1];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringFindOneBy($criteria, $caseSensitive);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringFindOneBy($criteria, $caseSensitive);
    }

    public function it_should_find_one_active_session(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => 0, '$top' => 1];
        $session       = $this->fakeSession();

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $session,
        ]));
        $this->findOneActiveBy($criteria, $caseSensitive)->shouldReturn($session);

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->findOneActiveBy($criteria, $caseSensitive)->shouldReturn(false);
    }

    public function it_should_throw_on_find_one_active_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => 0, '$top' => 1];

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringFindOneActiveBy($criteria, $caseSensitive);

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringFindOneActiveBy($criteria, $caseSensitive);
    }

    public function it_should_find_one_active_session_infos(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => 0, '$top' => 1];
        $session       = $this->fakeSession();

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $session,
        ]));
        $this->findOneActiveInfosBy($criteria, $caseSensitive)->shouldReturn($session);

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->findOneActiveInfosBy($criteria, $caseSensitive)->shouldReturn(false);
    }

    public function it_should_throw_on_find_one_active_infos_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = ['$filter' => $this->criteriaToFilter($criteria, $caseSensitive), '$skip' => 0, '$top' => 1];

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringFindOneActiveInfosBy($criteria, $caseSensitive);

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringFindOneActiveInfosBy($criteria, $caseSensitive);
    }

    public function it_should_get_one_session(HttpClient $client)
    {
        $id      = 2;
        $options = ['$filter' => $this->criteriaToFilter(['Id' => $id]), '$skip' => 0, '$top' => 1];
        $session = $this->fakeSession();

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $session,
        ]));
        $this->get($id)->shouldReturn($session);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->get($id)->shouldReturn(false);
    }

    public function it_should_throw_on_get_if_got_unexpected_response(HttpClient $client)
    {
        $id      = 2;
        $options = ['$filter' => $this->criteriaToFilter(['Id' => $id]), '$skip' => 0, '$top' => 1];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGet($id);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGet($id);
    }

    public function it_should_get_all_active_sessions(HttpClient $client)
    {
        $options = ['$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeSession(),
            $this->fakeSession(),
        ]));
        $this->getActive(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->getActive(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_get_all_active_if_got_unexpected_response(HttpClient $client)
    {
        $options = ['$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetActive(self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActive', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetActive(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_get_all_active_session_infos(HttpClient $client)
    {
        $options = ['$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeSession(),
            $this->fakeSession(),
        ]));
        $this->getActiveInfos(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->getActiveInfos(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_get_all_active_infos_if_got_unexpected_response(HttpClient $client)
    {
        $options = ['$skip' => self::$skip, '$top' => self::$top, '$orderby' => self::$orderby];

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetActiveInfos(self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActiveInfos', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringGetActiveInfos(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_check_if_session_exists(HttpClient $client)
    {
        $id      = 2;
        $options = ['$filter' => $this->criteriaToFilter(['Id' => $id]), '$skip' => 0, '$top' => 1];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeSession(),
        ]));
        $this->has($id)->shouldReturn(true);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $this->has($id)->shouldReturn(false);
    }

    public function it_should_throw_on_has_if_got_unexpected_response(HttpClient $client)
    {
        $id      = 2;
        $options = ['$filter' => $this->criteriaToFilter(['Id' => $id]), '$skip' => 0, '$top' => 1];

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringHas($id);

        $client->get('Sessions/Get', $options)->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringHas($id);
    }

}
