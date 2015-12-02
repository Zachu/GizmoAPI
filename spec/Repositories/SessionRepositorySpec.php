<?php

namespace spec\Pisa\GizmoAPI\Repositories;

use PhpSpec\ObjectBehavior;
use Pisa\GizmoAPI\Contracts\HttpClient;

class SessionRepositorySpec extends ObjectBehavior
{
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

    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_find_active_sessions_by_criteria(HttpClient $client)
    {

    }

    public function it_should_throw_on_find_active_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_find_active_session_infos_by_criteria(HttpClient $client)
    {

    }

    public function it_should_throw_on_find_active_infos_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_find_sessions_by_criteria(HttpClient $client)
    {

    }

    public function it_should_throw_on_find_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_find_one_session(HttpClient $client)
    {

    }

    public function it_should_throw_on_find_one_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_find_one_active_session(HttpClient $client)
    {

    }

    public function it_should_throw_on_find_one_active_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_find_one_active_session_infos(HttpClient $client)
    {

    }

    public function it_should_throw_on_find_one_active_infos_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_get_one_session(HttpClient $client)
    {

    }

    public function it_should_throw_on_get_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_get_all_active_sessions(HttpClient $client)
    {

    }

    public function it_should_throw_on_get_all_active_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_get_all_active_session_infos(HttpClient $client)
    {

    }

    public function it_should_throw_on_get_all_active_infos_if_got_unexpected_response(HttpClient $client)
    {

    }

    public function it_should_check_if_session_exists(HttpClient $client)
    {

    }

}
