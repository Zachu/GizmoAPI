<?php namespace Pisa\Api\Gizmo\Adapters;

use GuzzleHttp\ClientInterface as HttpClient;

class HttpClientAdapter
{
    protected $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function get($url, array $parameters = null, array $options = null)
    {
        return $this->request('get', $url, $parameters, $options);
    }

    public function post($url, array $parameters = null, array $options = null)
    {
        return $this->request('post', $url, $parameters, $options);
    }

    public function put($url, array $parameters = null, array $options = null)
    {
        return $this->request('put', $url, $parameters, $options);
    }

    public function delete($url, array $parameters = null, array $options = null)
    {
        return $this->request('delete', $url, $parameters, $options);
    }

    public function request($method, $url, $parameters = null, array $options = null)
    {
        if ($parameters == null) {
            $parameters = [];
        }
        if ($options == null) {
            $options = [];
        }

        if (!empty($parameters)) {
            $options['query'] = $this->fixParameters($parameters);
        }

        $response = $this->client->request($method, $url, $options);
        return new HttpResponseAdapter($response);
    }

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
