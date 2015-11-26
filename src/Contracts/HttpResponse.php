<?php namespace Pisa\Api\Gizmo\Contracts;

interface HttpResponse
{
    /**
     * Gets the response body
     * @param  boolean $autodetect Autodetect the content type and give the response accordingly. Defaults to true
     * @return mixed               Response body. If autodetect is false, returns string.
     */
    public function getBody($autodetect = true);

    /**
     * Get the response headers
     * @return array
     */
    public function getHeaders();

    /**
     * Get JSON body
     * @return mixed     Response
     * @throws Exception on error
     */
    public function getJson();

    /**
     * Get the reason phrase for the according status code
     * @return string
     */
    public function getReasonPhrase();

    /**
     * Get the http status code
     * @return int
     */
    public function getStatusCode();

    /**
     * Get the body as a string
     * @return string
     */
    public function getString();

    /**
     * Get the content type
     * @return string
     */
    public function getType();
}
