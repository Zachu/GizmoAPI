<?php namespace Pisa\Api\Gizmo\Adapters;

use Exception;
use GuzzleHttp\Psr7\Response;
use Pisa\Api\Gizmo\Contracts\HttpResponse;

class GuzzleResponseAdapter implements HttpResponse
{
    protected $response;
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function getBody($autodetect = true)
    {
        if ($autodetect === false) {
            return (string) $this->response->getBody();
        } else {
            $contentType = explode(';', $this->getType())[0];
            if ($contentType === 'application/json') {
                return $this->getJson();
            } else {
                return $this->getString();
            }
        }
    }

    public function getString()
    {
        return (string) $this->response->getBody();
    }

    public function getJson()
    {
        $json = json_decode($this->getBody(false), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        } else {
            throw new Exception("Json error " . json_last_error_msg());
        }
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }

    public function getType()
    {
        return $this->response->getHeaderLine('Content-Type');
    }

    public function __toString()
    {
        return $this->getString();
    }
}
