<?php namespace spec\Pisa\GizmoAPI\Repositories;

use PhpSpec\ObjectBehavior;
use Pisa\GizmoAPI\Models\Host;
use spec\Pisa\GizmoAPI\Helper;
use Pisa\GizmoAPI\Contracts\Container;
use Pisa\GizmoAPI\Contracts\HttpClient;

class HostRepositorySpec extends ObjectBehavior
{
    protected static $skip    = 2;
    protected static $top     = 1;
    protected static $orderby = 'Number';

    public function Let(HttpClient $client, Container $ioc)
    {
        $this->beConstructedWith($ioc, $client);
    }

    //
    //  Construct
    //

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Repositories\HostRepository');
    }

    //
    // All
    //

    public function it_should_return_empty_array_for_all(HttpClient $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_return_all_hosts(HttpClient $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeHost(),
            Helper::fakeHost(),
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
        $this->all(self::$top, self::$skip, self::$orderby)->shouldContain($host);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Hosts/Get', [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Exception')->duringAll(self::$top, self::$skip, self::$orderby);
    }

    //
    // FindBy
    //

    public function it_finds_hosts_by_parameters(HttpClient $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', ['$filter' => "substringof('host',HostName)", '$skip' => 2, '$top' => 1, '$orderby' => 'Number'])->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeHost(),
            Helper::fakeHost(),
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldContain($host);
    }

    public function it_returns_empty_array_if_no_host_found_by_parameters(HttpClient $client, Container $ioc)
    {
        $client->get('Hosts/Get', [
            '$filter'  => "substringof('host',HostName)",
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldBeArray();
        $this->findBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_throws_on_find_hosts_by_parameters_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Hosts/Get', [
            '$filter'  => "substringof('host',HostName)",
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Exception')->duringFindBy(['HostName' => 'host'], true, self::$top, self::$skip, self::$orderby);
    }

    //
    // FindOneBy
    //

    public function it_finds_one_host_by_parameters(HttpClient $client, Container $ioc, Host $host)
    {
        $client->get('Hosts/Get', [
            '$filter' => "substringof('host',HostName)",
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::contentResponse([Helper::fakeHost()]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn($host);
    }

    public function it_returns_null_when_one_host_not_found(HttpClient $client, Container $ioc)
    {
        $client->get('Hosts/Get', [
            '$filter' => "substringof('host',HostName)",
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->findOneBy(['HostName' => 'host'], true)->shouldReturn(null);
    }

    public function it_throws_on_find_one_hosts_by_parameters_if_got_unexpected_response(HttpClient $client)
    {
        $client->get('Hosts/Get', [
            '$filter' => "substringof('host',HostName)",
            '$skip'   => 0,
            '$top'    => 1,
        ])->shouldBeCalled()->willReturn(Helper::trueResponse());

        $this->shouldThrow('\Exception')->duringFindOneBy(['HostName' => 'host'], true);
    }

    //
    // Get
    //

    public function it_gets_host(HttpClient $client, Container $ioc, Host $host)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(Helper::contentResponse(Helper::fakeHost()));
        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->get($id)->shouldReturn($host);

    }

    public function it_returns_null_on_get_host_when_host_not_found(HttpClient $client, Container $ioc, Host $host)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(Helper::nullResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->get($id)->shouldReturn(null);
    }

    public function it_throws_on_get_host_if_got_unexpected_response(HttpClient $client)
    {
        $id = 1;
        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Exception')->duringGet($id);
    }

    //
    // GetByNumber
    //

    public function it_gets_host_by_number(HttpClient $client, Container $ioc, Host $host)
    {
        $no = 1;
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeHost(),
            Helper::fakeHost(),
        ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(2);
        $this->getByNumber($no)->shouldContain($host);
    }

    public function it_returns_empty_array_if_no_host_found_by_number(HttpClient $client, Container $ioc, Host $host)
    {
        $no = 1;

        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->getByNumber($no)->shouldBeArray();
        $this->getByNumber($no)->shouldHaveCount(0);
    }

    public function it_throws_on_get_host_by_number_if_got_unexpected_response(HttpClient $client)
    {
        $no = 1;
        $client->get('Hosts/GetByNumber', ['hostNumber' => $no])->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Exception')->duringGetByNumber($no);
    }

    //
    // Has
    //

    public function it_return_true_if_host_exists(HttpClient $client, Container $ioc, Host $host)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(Helper::contentResponse([
            Helper::fakeHost(),
            Helper::fakeHost(),
        ]));
        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($host);
        $this->has($id)->shouldReturn(true);
    }

    public function it_returns_false_if_host_doesnt_exist(HttpClient $client, Container $ioc)
    {
        $id = 1;

        $client->get('Hosts/Get/' . $id)->shouldBeCalled()->willReturn(Helper::nullResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->has($id)->shouldReturn(false);
    }
}
