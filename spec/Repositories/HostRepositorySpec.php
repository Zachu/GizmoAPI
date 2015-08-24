<?php

namespace spec\Pisa\Api\Gizmo\Repositories;

use GuzzleHttp\Psr7\Response as HttpResponse;
use PhpSpec\ObjectBehavior;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Adapters\HttpResponseAdapter as HttpResponseAdapter;
use Pisa\Api\Gizmo\Models\Host;
use zachu\zioc\IoC;

class HostRepositorySpec extends ObjectBehavior
{
    protected static $httpHost;
    protected static $httpHosts;
    protected static $httpEmpty;
    protected static $httpSingleHost;
    protected static $httpNull;

    public function Let(HttpClient $client, IoC $ioc)
    {
        $this->beConstructedWith($client, $ioc);
        $this->shouldHaveType('Pisa\Api\Gizmo\Repositories\HostRepository');

        self::$httpEmpty = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode([])
        ));

        self::$httpHost = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(['Id' => 1])
        ));

        self::$httpHosts = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode([['Id' => 1], ['Id' => 2]])
        ));

        self::$httpSingleHost = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode([['Id' => 1]])
        ));

        self::$httpNull = new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(null)
        ));
    }

    public function it_should_get_all_hosts(HttpClient $client, IoC $ioc, Host $host)
    {
        //Empty list should return empty array
        $client->get('Hosts/Get', ['$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(self::$httpEmpty);
        $ioc->make('Host')->shouldNotBeCalled();
        $this->all(1, 2, 'Number')->shouldBeArray();
        $this->all(1, 2, 'Number')->shouldHaveCount(0);

        //List with items should return array of models
        $client->get('Hosts/Get', ['$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(self::$httpHosts);
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->all(1, 2, 'Number')->shouldBeArray();
        $this->all(1, 2, 'Number')->shouldHaveCount(2);
        $this->all(1, 2, 'Number')->shouldContain($host);
    }

    public function it_should_find_hosts_by_parameters(HttpClient $client, IoC $ioc, Host $host)
    {
        //Empty list should return empty array
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(self::$httpEmpty);
        $ioc->make('Host')->shouldNotBeCalled();
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldHaveCount(0);

        //List with items should return array of models
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(self::$httpHosts);
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldHaveCount(2);
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldContain($host);

    }

    public function it_should_find_one_host_by_parameters(HttpClient $client, IoC $ioc, Host $host)
    {
        //Should return null when not found
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$top' => 1])->shouldBeCalled()->willReturn(self::$httpEmpty);
        $ioc->make('Host')->shouldNotBeCalled();
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn(null);

        //Should return model object when found
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$top' => 1])->shouldBeCalled()->willReturn(self::$httpSingleHost);
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn($host);
    }

    public function it_should_get_host(HttpClient $client, IoC $ioc, Host $host)
    {
        $id = 1;

        //Should return null when not found
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(self::$httpNull);
        $ioc->make('Host')->shouldNotBeCalled();
        $this->get($id)->shouldReturn(null);

        //Should return model object when found
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(self::$httpHost);
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->get($id)->shouldReturn($host);
    }

    public function it_should_try_get_by_number(HttpClient $client, IoC $ioc, Host $host)
    {
        $no = 1;

        //Should return null when not found
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(self::$httpEmpty);
        $ioc->make('Host')->shouldNotBeCalled();
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(0);

        //Should return model object when found
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(self::$httpHosts);
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(2);
        $this->getByNumber($no)->shouldContain($host);
    }

/*
public function it_should_throw_on_updates(Host $host)
{
$this->shouldThrow('\Exception')->duringCreate($host);
$this->shouldThrow('\Exception')->duringUpdate($host);
$this->shouldThrow('\Exception')->duringDelete($host);
$this->shouldThrow('\Exception')->duringSave($host);
}
 */
    public function it_should_check_if_host_exists(HttpClient $client, IoC $ioc, Host $host)
    {
        $id = 1;
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(self::$httpNull);
        $this->has($id)->shouldReturn(false);

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(self::$httpHost);
        $ioc->make('Host')->willReturn($host);
        $this->has($id)->shouldReturn(true);
    }
}
