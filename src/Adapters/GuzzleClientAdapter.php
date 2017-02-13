<?php namespace Pisa\GizmoAPI\Adapters;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use GuzzleHttp\ClientInterface;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Adapters\GuzzleResponseAdapter as HttpResponse;

class GuzzleClientAdapter implements HttpClient
{
    /** @var ClientInterface */
    protected $client;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * Create a new response
     * @param ClientInterface $client Guzzle HTTP client
     * @param Loggerinterface $logger PSR-3 Logger Interface
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @uses \Pisa\GizmoAPI\Adapters\GuzzleClientAdapter::request()
     */
    public function delete($url, array $parameters = [], array $options = [])
    {
        return $this->request('delete', $url, $parameters, $options);
    }

    /**
     * @uses \Pisa\GizmoAPI\Adapters\GuzzleClientAdapter::request()
     */
    public function get($url, array $parameters = [], array $options = [])
    {
        return $this->request('get', $url, $parameters, $options);
    }

    /**
     * @uses \Pisa\GizmoAPI\Adapters\GuzzleClientAdapter::request()
     */
    public function post($url, array $parameters = [], array $options = [])
    {
        return $this->request('post', $url, $parameters, $options);
    }

    /**
     * @uses \Pisa\GizmoAPI\Adapters\GuzzleClientAdapter::request()
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

        $this->logRequest($method, $url, $parameters);

        $time     = microtime(true);
        $response = $this->client->request($method, $url, $options);
        $time     = microtime(true) - $time;

        $response = new HttpResponse($response);
        $this->logResponse(
            $method,
            $url,
            $parameters,
            $response,
            ['time' => round($time, 3)]
        );
        return $response;
    }

    /**
     * Log requests
     * @param  string $method     Http request method
     * @param  string $url        Request url
     * @param  array  $parameters Request parameters
     * @return void
     * @internal
     */
    protected function logRequest($method, $url, $parameters)
    {
        // if (in_array(strtoupper($method), ['GET'])) {
        //     $logLevel = LogLevel::DEBUG;
        // } else {
        //     $logLevel = LogLevel::INFO;
        // }
        $logLevel = LogLevel::DEBUG;

        $this->logger->log($logLevel, '[HTTP] Request: '
            . self::makeRequestString($method, $url, $parameters)
        );
    }

    /**
     * Log responses
     * @param  string       $method     HTTP request method
     * @param  string       $url        Request url
     * @param  array        $parameters Request parameters
     * @param  HttpResponse $response   Response that was
     * @param  array        $context    Additional info
     * @return void
     * @internal
     */
    protected function logResponse(
        $method,
        $url,
        $parameters,
        HttpResponse $response,
        array $context = []
    ) {
        $statusFamily = substr($response->getStatusCode(), 1, 1);
        if ($statusFamily == 5) {
            $logLevel = LogLevel::ERROR;
        } elseif ($statusFamily == 4) {
            $logLevel = LogLevel::WARNING;
        } else {
            $logLevel = LogLevel::DEBUG;
        }

        if (isset($context['time'])) {
            $time = $context['time'];
            if ($time > 10) {
                $logLevel = LogLevel::CRITICAL;
            } elseif ($time > 5) {
                $logLevel = LogLevel::WARNING;
            }
        }

        $this->logger->log($logLevel, '[HTTP] Response: '
            . self::makeRequestString($method, $url, $parameters), array_merge([
                'status' => $response->getStatusCode() . ' ' . $response->getReasonPhrase(),
                'length' => $response->getHeader('Content-Length')[0],
            ], $context)
        );
    }

    /**
     * Make a string representation from a requests
     * @param  string $method     HTTP request method
     * @param  string $url        Request url
     * @param  array  $parameters Request parameters
     * @return string
     * @example  GET http://www.example.com/hello.html?foo=bar&bar=baz
     */
    protected function makeRequestString($method, $url, array $parameters = [])
    {
        $string = strtoupper($method) . ' ' . $url;
        if (!empty($parameters)) {
            $string .= '?' . http_build_query($parameters);
        }

        return $string;
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
