<?php

namespace spec\Pisa\Api\Gizmo\Repositories;

use Illuminate\Contracts\Container\Container;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter;
use Pisa\Api\Gizmo\Models\Host;
use spec\Pisa\Api\Gizmo\ApiTester;
use spec\Pisa\Api\Gizmo\HttpResponses;

class HostRepositorySpec extends ApiTester
{
    protected static $skip    = 2;
    protected static $top     = 1;
    protected static $orderby = 'Number';

    public function Let(HttpClientAdapter $client, Container $ioc)
    {
        $this->beConstructedWith($ioc, $client);
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

    public function it_should_return_empty_array_for_all(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_return_all_hosts(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeHost(),
            $this->fakeHost(),
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldContain($host);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $client->get('Hosts/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);
    }

    //
    // FindBy
    //

    public function it_finds_hosts_by_parameters(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeHost(),
            $this->fakeHost(),
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldContain($host);
    }

    public function it_returns_empty_array_if_no_host_found_by_parameters(HttpClientAdapter $client, Container $ioc)
    {
        $client->get('Hosts/Get', [
            '$filter'  => "substringof('host',HostName)",
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_throws_on_find_hosts_by_parameters_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $client->get('Hosts/Get', [
            '$filter'  => "substringof('host',HostName)",
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringFindBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby);
    }

    //
    // FindOneBy
    //

    public function it_finds_one_host_by_parameters(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$filter' => "substringof('host',HostName)",
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::content([$this->fakeHost()]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn($host);
    }

    public function it_returns_null_when_one_host_not_found(HttpClientAdapter $client, Container $ioc)
    {
        $client->get('Hosts/Get', [
            '$filter' => "substringof('host',HostName)",
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn(null);
    }

    public function it_throws_on_find_one_hosts_by_parameters_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $client->get('Hosts/Get', [
            '$filter' => "substringof('host',HostName)",
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(HttpResponses::true());

        $this->shouldThrow('\Exception')->duringFindOneBy(['HostName' => 'host'], true);
    }

    //
    // Get
    //

    public function it_gets_host(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::content($this->fakeHost()));
        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->get($id)->shouldReturn($host);

    }

    public function it_returns_null_on_get_host_when_host_not_found(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::null());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->get($id)->shouldReturn(null);
    }

    public function it_throws_on_get_host_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $id = 1;
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGet($id);
    }

    //
    // GetByNumber
    //

    public function it_gets_host_by_number(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $no = 1;
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeHost(),
            $this->fakeHost(),
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(2);
        $this->getByNumber($no)->shouldContain($host);
    }

    public function it_returns_empty_array_if_no_host_found_by_number(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $no = 1;

        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(HttpResponses::emptyArray());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(0);
    }

    public function it_throws_on_get_host_by_number_if_got_unexpected_response(HttpClientAdapter $client)
    {
        $no = 1;
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(HttpResponses::true());
        $this->shouldThrow('\Exception')->duringGetByNumber($no);
    }

    //
    // Has
    //

    public function it_return_true_if_host_exists(HttpClientAdapter $client, Container $ioc, Host $host)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::content([
            $this->fakeHost(),
            $this->fakeHost(),
        ]));
        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->has($id)->shouldReturn(true);
    }

    public function it_returns_false_if_host_doesnt_exist(HttpClientAdapter $client, Container $ioc)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(HttpResponses::null());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->has($id)->shouldReturn(false);
    }
}
