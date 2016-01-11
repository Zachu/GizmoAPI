<?php namespace Pisa\GizmoAPI\Adapters;

use GuzzleHttp\Client as ConcreteClient;
use GuzzleHttp\ClientInterface;
use Pisa\GizmoAPI\Adapters\GuzzleResponseAdapter as HttpResponse;
use Pisa\GizmoAPI\Contracts\HttpClient;

class GuzzleClientAdapter implements HttpClient
{
    /** @var ClientInterface */
    protected $client;

    /**
     * Create a new response
     * @param ClientInterface|null $client If no client is given, one is created automatically
     */
    public function __construct(ClientInterface $client = null)
    {
        if ($client === null) {
            $client = new ConcreteClient;
        }

        $this->client = $client;
    }

    /**
     * @uses $this->request() This is a wrapper for request()
     */
    public function delete($url, array $parameters = [], array $options = [])
    {
        return $this->request('delete', $url, $parameters, $options);
    }

    /**
     * @uses $this->request() This is a wrapper for request()
     */
    public function get($url, array $parameters = [], array $options = [])
    {
        return $this->request('get', $url, $parameters, $options);
    }

    /**
     * @uses $this->request() This is a wrapper for request()
     */
    public function post($url, array $parameters = [], array $options = [])
    {
        return $this->request('post', $url, $parameters, $options);
    }

    /**
     * @uses $this->request() This is a wrapper for request()
     */
    public function put($url, array $parameters = [], array $options = [])
    {
        return $this->request('put', $url, $parameters, $options);
    }

    /**
     * Perform the HTTP request
     *
     * @param  string $method     HTTP method/verb
     * @param  string $url        URL to send the request
     * @param  array  $parameters Key/Value pairs to form the query string
     * @param  array  $options    Options to pass straight to GuzzleClient
     *
     * @return \Pisa\GizmoAPI\Adapters\GuzzleResponseAdapter
     */
    public function request($method, $url, array $parameters = [], array $options = [])
    {
        if (!empty($parameters)) {
            $options['query'] = $this->fixParameters($parameters);
        }
        $response = $this->client->request($method, $url, $options);
        return new HttpResponse($response);
    }

    /**
     * Converts URL parameters boolean and null parameters to string representations.
     * @param  array  $parameters URL parameters
     * @return array
     *
     * @internal
     */
    private function fixParameters(array $parameters = [])
    {
        foreach ($parameters as $key => $param) {
            if ($param === null) {
                $parameters[$key] = '';
            } elseif (is_bool($param)) {
                $parameters[$key] = ($param ? 'true' : 'false');
            }
        }

        return $parameters;
    }
}
