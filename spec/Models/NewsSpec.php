<?php namespace spec\Pisa\GizmoAPI\Models;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use spec\Pisa\GizmoAPI\Helper;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;

class NewsSpec extends ObjectBehavior
{
    public function Let(HttpClient $client, Factory $factory, Validator $validator)
    {
        $this->beConstructedWith($client, $factory, Helper::fakeNews());
        $factory->make(Argument::any(), Argument::any())->willReturn($validator);
        $validator->fails()->willReturn(false);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Models\News');
    }

    public function it_should_create_news(HttpClient $client, Factory $factory)
    {
        $this->beConstructedWith($client, $factory, Helper::fakeNews(['Id' => null, 'Date' => null]));

        $client->put('News/Add', $this->getAttributes())->shouldBeCalled()->willReturn(Helper::noContentResponse());
        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_create_if_got_unexpected_response(HttpClient $client, Factory $factory)
    {
        $this->beConstructedWith($client, $factory, Helper::fakeNews(['Id' => null, 'Date' => null]));

        $client->put('News/Add', $this->getAttributes())->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Exception')->duringSave();

        $client->put('News/Add', $this->getAttributes())->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Exception')->duringSave();
    }

    public function it_should_update_news(HttpClient $client)
    {
        $this->Title = 'NewTitle';

        $client->post('News/Update', $this->getAttributes())->shouldBeCalled()->willReturn(Helper::noContentResponse());
        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_update_if_got_unexpected_response(HttpClient $client)
    {
        $this->Title = 'NewTitle';

        $client->post('News/Update', $this->getAttributes())->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Exception')->duringSave();

        $client->post('News/Update', $this->getAttributes())->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Exception')->duringSave();
    }

    public function it_should_delete_news(HttpClient $client)
    {
        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->exists()->shouldBe(true);
        $this->delete();
        $this->exists()->shouldBe(false);
    }

    public function it_should_throw_on_delete_if_news_doesnt_exist(HttpClient $client, Factory $factory)
    {
        $this->beConstructedWith($client, $factory, Helper::fakeNews(['Id' => null, 'Date' => null]));
        $this->shouldThrow('\Exception')->duringDelete();
    }

    public function it_should_throw_on_delete_if_got_unexpected_response(HttpClient $client)
    {
        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Exception')->duringDelete();

        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Exception')->duringDelete();
    }
}
