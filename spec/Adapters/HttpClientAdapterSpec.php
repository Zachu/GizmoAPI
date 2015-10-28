<?php

namespace spec\Pisa\Api\Gizmo\Adapters;

use GuzzleHttp\ClientInterface as HttpClient;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PhpSpec\ObjectBehavior;

class HttpClientAdapterSpec extends ObjectBehavior
{
    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable(HttpClient $client)
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Adapters\HttpClientAdapter');
    }

    public function it_should_send_get_requests(HttpClient $client, HttpResponse $response)
    {
        $url = 'http://www.example.com';

        $client->request('get', $url, [])->shouldBeCalled();
        $client->request('get', $url, [])->willReturn($response);

        $this->get($url)->shouldHaveType('Pisa\Api\Gizmo\Adapters\HttpResponseAdapter');
    }

    public function it_should_send_post_requests(HttpClient $client, HttpResponse $response)
    {
        $url = 'http://www.example.com';

        $client->request('post', $url, [])->shouldBeCalled();
        $client->request('post', $url, [])->willReturn($response);

        $this->post($url)->shouldHaveType('Pisa\Api\Gizmo\Adapters\HttpResponseAdapter');
    }

    public function it_includes_parameters(HttpClient $client, HttpResponse $response)
    {
        $url    = 'http://www.example.com';
        $params = ['foo' => 'bar'];

        $client->request('get', $url, ['query' => $params])->shouldBeCalled();
        $client->request('get', $url, ['query' => $params])->willReturn($response);
        $this->get($url, $params)->shouldHaveType('Pisa\Api\Gizmo\Adapters\HttpResponseAdapter');
    }
}
