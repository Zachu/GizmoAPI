<?php namespace spec\Pisa\Api\Gizmo;

use GuzzleHttp\Psr7\Response as HttpResponse;
use Pisa\Api\Gizmo\Adapters\HttpResponseAdapter as HttpResponseAdapter;

class HttpResponses
{
    public static function noContent()
    {
        return new HttpResponseAdapter(new HttpResponse(204));
    }

    public static function false()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(false)
        ));
    }

    public static function true()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(true)
        ));
    }

    public static function zero()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(0)
        ));
    }

    public static function one()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(1)
        ));
    }

    public static function time()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(date('c'))
        ));
    }
}
