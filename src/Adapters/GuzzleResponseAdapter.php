<?php namespace Pisa\GizmoAPI\Adapters;

use GuzzleHttp\Psr7\Response;
use Pisa\GizmoAPI\Contracts\HttpResponse;
use Pisa\GizmoAPI\Exceptions\UnexpectedResponseException;

class GuzzleResponseAdapter implements HttpResponse
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function __toString()
    {
        return $this->getString();
    }

    public function assertArray()
    {
        if (!is_array($this->getBody())) {
            throw new UnexpectedResponseException(
                "Unexpected response body " . gettype($this->getBody()) . ". Expecting array"
            );
        }
    }

    public function assertBoolean()
    {
        if (!is_bool($this->getBody())) {
            throw new UnexpectedResponseException(
                "Unexpected response body " . gettype($this->getBody()) . ". Expecting boolean"
            );
        }
    }

    public function assertEmpty()
    {
        if ($this->getBody() != '') {
            throw new UnexpectedResponseException(
                "Unexpected response body " . gettype($this->getBody()) . ". Expecting none"
            );
        }
    }

    public function assertInteger()
    {
        if (!is_int($this->getBody())) {
            throw new UnexpectedResponseException(
                "Unexpected response body " . gettype($this->getBody()) . ". Expecting integer"
            );
        }
    }

    public function assertStatusCodes($statusCodes = [])
    {
        if (is_numeric($statusCodes)) {
            $statusCodes = [(int) $statusCodes];
        }

        if (!in_array($this->getStatusCode(), $statusCodes)) {
            throw new UnexpectedResponseException(
                "Unexpected HTTP Code " . $this->getStatusCode()
                . ". Expecting " . implode(',', $statusCodes)
            );
        }
    }

    public function assertString()
    {
        if (!is_string($this->getBody())) {
            throw new UnexpectedResponseException(
                "Unexpected response body " . gettype($body) . ". Expecting string"
            );
        }
    }

    public function assertTime()
    {
        $body = $this->getBody();
        if (is_int($body) && $body < 0) {
            throw new UnexpectedResponseException(
                "Unexpected response body negative integer. "
                . "Expecting time (positive integer or strtotime parseable string)"
            );
        } elseif (is_string($body) && strtotime($body) === false) {
            throw new UnexpectedResponseException(
                "Unexpected response body unparseable string. "
                . "Expecting time (positive integer or strtotime parseable string)"
            );
        } elseif (!is_int($body) && !is_string($body)) {
            throw new UnexpectedResponseException(
                "Unexpected response body " . gettype($body) . ". "
                . "Expecting time (positive integer or strtotime parseable string)"
            );
        }
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

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function getJson()
    {
        $json = json_decode($this->getBody(false), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        } else {
            throw new UnexpectedResponseException("Json error " . json_last_error_msg());
        }
    }

    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getString()
    {
        return (string) $this->response->getBody();
    }

    public function getType()
    {
        return $this->response->getHeaderLine('Content-Type');
    }
}
