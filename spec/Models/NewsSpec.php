<?php namespace spec\Pisa\GizmoAPI\Models;

use Pisa\GizmoAPI\Contracts\HttpClient;
use spec\Pisa\GizmoAPI\ApiTester;
use spec\Pisa\GizmoAPI\HttpResponses;

class NewsSpec extends ApiTester
{
    public function Let(HttpClient $client)
    {
        $this->beConstructedWith($client, $this->fakeNews());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Models\News');
    }

    public function it_should_create_news(HttpClient $client)
    {
        $this->beConstructedWith($client, $this->fakeNews(['Id' => null, 'Date' => null]));

        $client->put('News/Add', $this->getAttributes())->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_create_if_got_unexpected_response(HttpClient $client)
    {
        $this->beConstructedWith($client, $this->fakeNews(['Id' => null, 'Date' => null]));

        $client->put('News/Add', $this->getAttributes())->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringSave();

        $client->put('News/Add', $this->getAttributes())->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringSave();
    }

    public function it_should_update_news(HttpClient $client)
    {
        $this->Title = 'NewTitle';

        $client->post('News/Update', $this->getAttributes())->shouldBeCalled()->willReturn(HttpResponses::noContent());
        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_update_if_got_unexpected_response(HttpClient $client)
    {
        $this->Title = 'NewTitle';

        $client->post('News/Update', $this->getAttributes())->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringSave();

        $client->post('News/Update', $this->getAttributes())->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringSave();
    }

    public function it_should_delete_news(HttpClient $client)
    {
        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::noContent());

        $this->exists()->shouldBe(true);
        $this->delete();
        $this->exists()->shouldBe(false);
    }

    public function it_should_throw_on_delete_if_news_doesnt_exist(HttpClient $client)
    {
        $this->beConstructedWith($client, $this->fakeNews(['Id' => null, 'Date' => null]));
        $this->shouldThrow('\Exception')->duringDelete();
    }

    public function it_should_throw_on_delete_if_got_unexpected_response(HttpClient $client)
    {
        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringDelete();

        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(HttpResponses::internalServerError());
        $this->shouldThrow('\Exception')->duringDelete();
    }
}
