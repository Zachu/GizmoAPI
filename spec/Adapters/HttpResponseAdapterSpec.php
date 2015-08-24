<?php namespace spec\Pisa\Api\Gizmo\Adapters;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use GuzzleHttp\Psr7\Response as HttpResponse;

class HttpResponseAdapterSpec extends ObjectBehavior
{
    function let(HttpResponse $response)
    {
        $this->beConstructedWith($response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Adapters\HttpResponseAdapter');
    }

    function it_should_show_body()
    {
        $body = 'testBody';
        $response = new HttpResponse(200, [], $body);
        $this->beConstructedWith($response);
        $this->getBody()->shouldEqual($body);

    }

    function it_should_show_success_status_code()
    {
        $body = 'testBody';
        $statusCode = 200;
        $response = new HttpResponse($statusCode, [], $body);
        $this->beConstructedWith($response);
        $this->getStatusCode()->shouldEqual($statusCode);
        $this->getReasonPhrase()->shouldEqual('OK');
    }

    function it_should_show_internal_error_status_code()
    {
        $body = 'testBody';
        $statusCode = 500;
        $response = new HttpResponse($statusCode, [], $body);
        $this->beConstructedWith($response);
        $this->getStatusCode()->shouldEqual($statusCode);
        $this->getReasonPhrase()->shouldEqual('Internal Server Error');
    }
}
