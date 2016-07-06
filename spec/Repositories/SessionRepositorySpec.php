<?php namespace spec\Pisa\GizmoAPI\Repositories;

use PhpSpec\ObjectBehavior;
use spec\Pisa\GizmoAPI\Helper;
use Pisa\GizmoAPI\Contracts\HttpClient;

class SessionRepositorySpec extends ObjectBehavior
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
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeSession(),
            Helper::fakeSession(),
        ]));
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClient $client)
    {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringAll(self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringAll(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_active_sessions_by_criteria(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeSession(),
            Helper::fakeSession(),
        ]));
        $this->findActiveBy(
            $criteria,
            $caseSensitive,
            self::$top,
            self::$skip,
            self::$orderby
        )->shouldHaveCount(2);

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->findActiveBy(
            $criteria,
            $caseSensitive,
            self::$top,
            self::$skip,
            self::$orderby
        )->shouldHaveCount(0);
    }

    public function it_should_throw_on_find_active_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindActiveBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindActiveBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_active_session_infos_by_criteria(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeSession(),
            Helper::fakeSession(),
        ]));
        $this->findActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->findActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_find_active_infos_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindActiveInfosBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby);
    }

    public function it_should_find_sessions_by_criteria(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeSession(),
            Helper::fakeSession(),
        ]));
        $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->findBy($criteria, $caseSensitive, self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_find_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindBy(
                $criteria,
                $caseSensitive,
                self::$top,
                self::$skip,
                self::$orderby
            );

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindBy(
                $criteria,
                $caseSensitive,
                self::$top,
                self::$skip,
                self::$orderby
            );
    }

    public function it_should_find_one_session(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];
        $session = Helper::fakeSession();

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            $session,
        ]));
        $this->findOneBy($criteria, $caseSensitive)->shouldReturn($session);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->findOneBy($criteria, $caseSensitive)->shouldReturn(null);
    }

    public function it_should_throw_on_find_one_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindOneBy($criteria, $caseSensitive);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindOneBy($criteria, $caseSensitive);
    }

    public function it_should_find_one_active_session(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];
        $session = Helper::fakeSession();

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            $session,
        ]));
        $this->findOneActiveBy($criteria, $caseSensitive)->shouldReturn($session);

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->findOneActiveBy($criteria, $caseSensitive)->shouldReturn(null);
    }

    public function it_should_throw_on_find_one_active_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindOneActiveBy($criteria, $caseSensitive);

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindOneActiveBy($criteria, $caseSensitive);
    }

    public function it_should_find_one_active_session_infos(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];
        $session = Helper::fakeSession();

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            $session,
        ]));
        $this->findOneActiveInfosBy($criteria, $caseSensitive)->shouldReturn($session);

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->findOneActiveInfosBy($criteria, $caseSensitive)->shouldReturn(null);
    }

    public function it_should_throw_on_find_one_active_infos_if_got_unexpected_response(HttpClient $client)
    {
        $criteria      = ['UserId' => 1];
        $caseSensitive = false;
        $options       = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindOneActiveInfosBy($criteria, $caseSensitive);

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindOneActiveInfosBy($criteria, $caseSensitive);
    }

    public function it_should_get_one_session(HttpClient $client)
    {
        $id      = 2;
        $options = [
            '$filter' => $this->criteriaToFilter(['Id' => $id]),
            '$skip'   => 0,
            '$top'    => 1,
        ];
        $session = Helper::fakeSession();

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            $session,
        ]));
        $this->get($id)->shouldReturn($session);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->get($id)->shouldReturn(null);
    }

    public function it_should_throw_on_get_if_got_unexpected_response(HttpClient $client)
    {
        $id      = 2;
        $options = [
            '$filter' => $this->criteriaToFilter(['Id' => $id]),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGet($id);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGet($id);
    }

    public function it_should_get_all_active_sessions(HttpClient $client)
    {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeSession(),
            Helper::fakeSession(),
        ]));
        $this->getActive(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->getActive(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_get_all_active_if_got_unexpected_response(HttpClient $client)
    {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetActive(self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActive', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetActive(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_get_all_active_session_infos(HttpClient $client)
    {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeSession(),
            Helper::fakeSession(),
        ]));
        $this->getActiveInfos(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->getActiveInfos(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_get_all_active_infos_if_got_unexpected_response(HttpClient $client)
    {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetActiveInfos(self::$top, self::$skip, self::$orderby);

        $client->get('Sessions/GetActiveInfos', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringGetActiveInfos(self::$top, self::$skip, self::$orderby);
    }

    public function it_should_check_if_session_exists(HttpClient $client)
    {
        $id      = 2;
        $options = [
            '$filter' => $this->criteriaToFilter(['Id' => $id]),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeSession(),
        ]));
        $this->has($id)->shouldReturn(true);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $this->has($id)->shouldReturn(false);
    }

    public function it_should_throw_on_has_if_got_unexpected_response(HttpClient $client)
    {
        $id      = 2;
        $options = [
            '$filter' => $this->criteriaToFilter(['Id' => $id]),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringHas($id);

        $client->get('Sessions/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringHas($id);
    }

}
