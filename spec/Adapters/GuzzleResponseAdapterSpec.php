<?php namespace spec\Pisa\GizmoAPI\Adapters;

use PhpSpec\ObjectBehavior;
use GuzzleHttp\Psr7\Response as HttpResponse;

class GuzzleResponseAdapterSpec extends ObjectBehavior
{
    public function let(HttpResponse $response)
    {
        $this->beConstructedWith($response);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Adapters\GuzzleResponseAdapter');
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

    public function it_should_throw_when_expecting_others_than_string()
    {
        $body     = 'testBody';
        $response = new HttpResponse(200, [], $body);
        $this->beConstructedWith($response);

        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertArray');
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertBoolean');
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertEmpty');
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertInteger');
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertTime');
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertStatusCodes', [404]);

        $this->shouldNotThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertString');
        $this->shouldNotThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')->
            during('assertStatusCodes', [200]);
    }
}
