<?php namespace spec\Pisa\Api\Gizmo\Adapters;

use GuzzleHttp\Psr7\Response as HttpResponse;
use PhpSpec\ObjectBehavior;

class GuzzleResponseAdapterSpec extends ObjectBehavior
{
    public function let(HttpResponse $response)
    {
        $this->beConstructedWith($response);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Adapters\GuzzleResponseAdapter');
    }

    public function it_should_show_body()
    {
        $body     = 'testBody';
        $response = new HttpResponse(200, [], $body);
        $this->beConstructedWith($response);
        $this->getBody()->shouldEqual($body);

    }

    public function it_should_show_success_status_code()
    {
        $body       = 'testBody';
        $statusCode = 200;
        $response   = new HttpResponse($statusCode, [], $body);
        $this->beConstructedWith($response);
        $this->getStatusCode()->shouldEqual($statusCode);
        $this->getReasonPhrase()->shouldEqual('OK');
    }

    public function it_should_show_internal_error_status_code()
    {
        $body       = 'testBody';
        $statusCode = 500;
        $response   = new HttpResponse($statusCode, [], $body);
        $this->beConstructedWith($response);
        $this->getStatusCode()->shouldEqual($statusCode);
        $this->getReasonPhrase()->shouldEqual('Internal Server Error');
    }
}
