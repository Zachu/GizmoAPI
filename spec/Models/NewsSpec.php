<?php namespace spec\Pisa\GizmoAPI\Models;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use spec\Pisa\GizmoAPI\Helper;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;

class NewsSpec extends ObjectBehavior
{
    public function let(
        HttpClient $client,
        Factory $factory,
        Validator $validator,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($client, $factory, $logger, Helper::fakeNews());
        $factory->make(Argument::any(), Argument::any())->willReturn($validator);
        $validator->fails()->willReturn(false);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Models\News');
    }

    public function it_should_create_news(
        HttpClient $client,
        Factory $factory,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($client, $factory, $logger, Helper::fakeNews([
            'Id'   => null,
            'Date' => null,
        ]));

        $client->put('News/Add', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_create_if_got_unexpected_response(
        HttpClient $client,
        Factory $factory,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($client, $factory, $logger, Helper::fakeNews([
            'Id'   => null,
            'Date' => null,
        ]));

        $client->put('News/Add', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSave();

        $client->put('News/Add', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSave();
    }

    public function it_should_update_news(HttpClient $client)
    {
        $this->Title = 'NewTitle';

        $client->post('News/Update', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::noContentResponse());

        $this->save()->shouldReturn($this);
    }

    public function it_should_throw_on_update_if_got_unexpected_response(HttpClient $client)
    {
        $this->Title = 'NewTitle';

        $client->post('News/Update', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSave();

        $client->post('News/Update', $this->getAttributes())
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringSave();
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

    public function it_should_throw_on_delete_if_news_doesnt_exist(
        HttpClient $client,
        Factory $factory,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($client, $factory, $logger, Helper::fakeNews([
            'Id'   => null,
            'Date' => null,
        ]));

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\RequirementException')
            ->duringDelete();
    }

    public function it_should_throw_on_delete_if_got_unexpected_response(HttpClient $client)
    {
        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringDelete();

        $client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ])->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringDelete();
    }
}
