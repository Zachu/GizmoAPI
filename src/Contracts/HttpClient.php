<?php namespace Pisa\GizmoAPI\Contracts;

interface HttpClient
{
    /**
     * Perform a HTTP DELETE request
     * @param  string                                 $url        URL to send the request
     * @param  array                                  $parameters Key/Value pairs to form the query string
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\GizmoAPI\Contracts\HttpResponse  Http response
     */
    public function delete($url, array $parameters = [], array $options = []);

    /**
     * Perform a HTTP GET request
     * @param  string                                 $url        URL to send the request
     * @param  array                                  $parameters Key/Value pairs to form the query string
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\GizmoAPI\Contracts\HttpResponse  Http response
     */
    public function get($url, array $parameters = [], array $options = []);

    /**
     * Perform a HTTP POST request
     * @param  string                                 $url        URL to send the request
     * @param  array                                  $parameters Key/Value pairs to form the query string
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\GizmoAPI\Contracts\HttpResponse  Http response
     */
    public function post($url, array $parameters = [], array $options = []);

    /**
     * Perform a HTTP PUT request
     * @param  string                                 $url        URL
     * @param  string                                 $url        URL to send the request
     * @param  array                                  $parameters Key/Value pairs to form the query string
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\GizmoAPI\Contracts\HttpResponse  Http response
     */
    public function put($url, array $parameters = [], array $options = []);

    /**
     * Perform a custom HTTP request
     * @param  string                                 $method     HTTP method/verb
     * @param  string                                 $url        URL to send the request
     * @param  array                                  $parameters Key/Value pairs to form the query string
     * @param  array                                  $options    Options for the underlying HTTP Client
     * @return \Pisa\GizmoAPI\Contracts\HttpResponse  Http response
     */
    public function request($method, $url, array $parameters = [], array $options = []);
}
