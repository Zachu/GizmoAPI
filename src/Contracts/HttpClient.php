<?php namespace Pisa\Api\Gizmo\Contracts;

interface HttpClient
{
    /**
     * Perform a HTTP DELETE request
     * @param  string                                 $url        URL
     * @param  array                                  $parameters Parameters to append to the request
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\Api\Gizmo\Contracts\HttpResponse Http response
     */
    public function delete($url, array $parameters = [], array $options = []);

    /**
     * Perform a HTTP GET request
     * @param  string                                 $url        URL
     * @param  array                                  $parameters Parameters to append to the request
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\Api\Gizmo\Contracts\HttpResponse Http response
     */
    public function get($url, array $parameters = [], array $options = []);

    /**
     * Perform a HTTP POST request
     * @param  string                                 $url        URL
     * @param  array                                  $parameters Parameters to append to the request
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\Api\Gizmo\Contracts\HttpResponse Http response
     */
    public function post($url, array $parameters = [], array $options = []);

    /**
     * Perform a HTTP PUT request
     * @param  string                                 $url        URL
     * @param  array                                  $parameters Parameters to append to the request
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\Api\Gizmo\Contracts\HttpResponse Http response
     */
    public function put($url, array $parameters = [], array $options = []);

    /**
     * Perform a custom HTTP request
     * @param  string                                 $method     Http method
     * @param  string                                 $url        URL
     * @param  array                                  $parameters Parameters to append to the request
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\Api\Gizmo\Contracts\HttpResponse Http response
     */
    public function request($method, $url, array $parameters = [], array $options = []);
}
