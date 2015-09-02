<?php

namespace spec\Pisa\Api\Gizmo\Repositories;

use PhpSpec\ObjectBehavior;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Models\Host;
use spec\Pisa\Api\Gizmo\HttpResponses;
use zachu\zioc\IoC;

class HostRepositorySpec extends ObjectBehavior
{
    protected static $skip = 2;
    protected static $top = 1;
    protected static $orderby = 'Number';

    public function Let(HttpClient $client, IoC $ioc)
    {
        $this->beConstructedWith($client, $ioc);
    }

    //
    //  Construct
    //

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\Api\Gizmo\Repositories\HostRepository');
    }

    //
    // All
    //

    public function it_should_return_empty_array_for_all(HttpClient $client, IoC $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$skip' => self::$skip,
            '$top' => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $ioc->make('Host')->shouldNotBeCalled();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_return_all_hosts(HttpClient $client, IoC $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$skip' => self::$skip,
            '$top' => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldContain($host);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Hosts/Get', [
            '$skip' => self::$skip,
            '$top' => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);
    }

    //
    //
    //

    public function it_should_find_hosts_by_parameters(HttpClient $client, IoC $ioc, Host $host)
    {
        //Empty list should return empty array
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $ioc->make('Host')->shouldNotBeCalled();
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldHaveCount(0);

        //List with items should return array of models
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));

        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldHaveCount(2);
        $this->findBy(['HostName' => 'host'], true, 1, 2, 'Number')->shouldContain($host);
    }

    //
    //
    //

    public function it_should_find_one_host_by_parameters(HttpClient $client, IoC $ioc, Host $host)
    {
        //Should return null when not found
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$top' => 1])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $ioc->make('Host')->shouldNotBeCalled();
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn(null);

        //Should return model object when found
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$top' => 1])->shouldBeCalled()->willReturn(HttpResponses::content([['Id' => 1]]));
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn($host);
    }

    //
    //
    //

    public function it_should_get_host(HttpClient $client, IoC $ioc, Host $host)
    {
        $id = 1;

        //Should return null when not found
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::null());
        $ioc->make('Host')->shouldNotBeCalled();
        $this->get($id)->shouldReturn(null);

        //Should return model object when found
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->get($id)->shouldReturn($host);
    }

    //
    //
    //

    public function it_should_try_get_by_number(HttpClient $client, IoC $ioc, Host $host)
    {
        $no = 1;

        //Should return null when not found
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $ioc->make('Host')->shouldNotBeCalled();
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(0);

        //Should return model object when found
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));
        $ioc->make('Host')->shouldBeCalled()->willReturn($host);
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(2);
        $this->getByNumber($no)->shouldContain($host);
    }

    //
    //
    //

/*
public function it_should_throw_on_updates(Host $host)
{
$this->shouldThrow('\Exception')->duringCreate($host);
$this->shouldThrow('\Exception')->duringUpdate($host);
$this->shouldThrow('\Exception')->duringDelete($host);
$this->shouldThrow('\Exception')->duringSave($host);
}
 */

    //
    //
    //

    public function it_should_check_if_host_exists(HttpClient $client, IoC $ioc, Host $host)
    {
        $id = 1;
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::null());
        $this->has($id)->shouldReturn(false);

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::content([
            ['Id' => 1],
            ['Id' => 2],
        ]));
        $ioc->make('Host')->willReturn($host);
        $this->has($id)->shouldReturn(true);
    }
}
