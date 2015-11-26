<?php namespace spec\Pisa\Api\Gizmo\Adapters;

use GuzzleHttp\ClientInterface as HttpClient;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Pisa\Api\Gizmo\Adapters\GuzzleResponseAdapter;

class GuzzleClientAdapterSpec extends ObjectBehavior
{
    public function let(HttpClient $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable(HttpClient $client)
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Adapters\GuzzleClientAdapter');
    }

    public function it_should_send_get_requests(HttpClient $client, Response $response)
    {
        $url = 'http://www.example.com';

        $client->request('get', $url, [])->shouldBeCalled();
        $client->request('get', $url, [])->willReturn($response);

        $this->get($url)->shouldHaveType(GuzzleResponseAdapter::class);
    }

    public function it_should_send_post_requests(HttpClient $client, Response $response)
    {
        $url = 'http://www.example.com';

        $client->request('post', $url, [])->shouldBeCalled();
        $client->request('post', $url, [])->willReturn($response);

        $this->post($url)->shouldHaveType(GuzzleResponseAdapter::class);
    }

    public function it_includes_parameters(HttpClient $client, Response $response)
    {
        $url    = 'http://www.example.com';
        $params = ['foo' => 'bar'];

        $client->request('get', $url, ['query' => $params])->shouldBeCalled();
        $client->request('get', $url, ['query' => $params])->willReturn($response);
        $this->get($url, $params)->shouldHaveType(GuzzleResponseAdapter::class);
    }
}
