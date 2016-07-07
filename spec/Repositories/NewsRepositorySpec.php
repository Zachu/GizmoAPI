<?php namespace spec\Pisa\GizmoAPI\Repositories;

use PhpSpec\ObjectBehavior;
use spec\Pisa\GizmoAPI\Helper;
use Pisa\GizmoAPI\Contracts\Container;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Models\NewsInterface;

class NewsRepositorySpec extends ObjectBehavior
{
    protected static $skip    = 2;
    protected static $top     = 1;
    protected static $orderby = 'Number';

    public function Let(HttpClient $client, Container $ioc)
    {
        $this->beConstructedWith($ioc, $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Pisa\GizmoAPI\Repositories\NewsRepository');
    }

    //
    // All
    //

    public function it_should_get_all_news(HttpClient $client, Container $ioc)
    {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('News/Get', $options)->shouldBeCalled()->willReturn(
            Helper::contentResponse([
                Helper::fakeNews(),
                Helper::fakeNews(),
            ]));

        $ioc->make($this->fqnModel())->shouldBeCalled();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(2);
    }

    public function it_should_return_empty_array_for_all(
        HttpClient $client,
        Container $ioc
    ) {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('News/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->all(self::$top, self::$skip, self::$orderby)->shouldHaveCount(0);
    }

    public function it_should_throw_on_all_if_got_unexpected_response(HttpClient $client)
    {
        $options = [
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('News/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringAll(self::$top, self::$skip, self::$orderby);

        $client->get('News/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::trueResponse());
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringAll(self::$top, self::$skip, self::$orderby);
    }

    //
    // FindBy
    //

    public function it_should_find_news_by_parameters(
        HttpClient $client,
        Container $ioc
    ) {
        $criteria      = ['Title' => 'Foo'];
        $caseSensitive = false;

        $options = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('News/Get', $options)->shouldBeCalled()->willReturn(
            Helper::contentResponse([
                Helper::fakeNews(),
                Helper::fakeNews(),
            ]));

        $ioc->make($this->fqnModel())->shouldBeCalled();
        $this->findBy(
            $criteria,
            $caseSensitive,
            self::$top,
            self::$skip,
            self::$orderby
        )->shouldHaveCount(2);
    }

    public function it_should_return_empty_array_for_findby(
        HttpClient $client,
        Container $ioc
    ) {
        $criteria      = ['Title' => 'Foo'];
        $caseSensitive = true;

        $options = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('News/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->findBy(
            $criteria,
            $caseSensitive,
            self::$top,
            self::$skip,
            self::$orderby
        )->shouldHaveCount(0);
    }

    public function it_should_throw_on_findby_if_got_unexpected_response(
        HttpClient $client,
        Container $ioc
    ) {
        $criteria      = ['Title' => 'Foo'];
        $caseSensitive = false;

        $options = [
            '$filter'  => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'    => self::$skip,
            '$top'     => self::$top,
            '$orderby' => self::$orderby,
        ];

        $client->get('News/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::internalServerErrorResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindBy(
                $criteria,
                $caseSensitive,
                self::$top,
                self::$skip,
                self::$orderby
            );

        $client->get('News/Get', $options)->shouldBeCalled()->willReturn(Helper::trueResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->shouldThrow('\Pisa\GizmoAPI\Exceptions\UnexpectedResponseException')
            ->duringFindBy(
                $criteria,
                $caseSensitive,
                self::$top,
                self::$skip,
                self::$orderby
            );
    }

    //
    // Find One By
    //

    public function it_should_find_one_by_parameters(
        HttpClient $client,
        Container $ioc,
        NewsInterface $news
    ) {
        $criteria      = ['Title' => 'Foo'];
        $caseSensitive = false;

        $options = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('News/Get', $options)->shouldBeCalled()->willReturn(
            Helper::contentResponse([
                Helper::fakeNews(),
            ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($news);
        $this->findOneBy($criteria, $caseSensitive)->shouldReturn($news);
    }

    public function it_should_return_null_if_nothing_was_found_on_find_one_by_parameters(
        HttpClient $client,
        Container $ioc
    ) {
        $criteria      = ['Title' => 'Foo'];
        $caseSensitive = false;

        $options = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('News/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());
        $ioc->make($this->fqnModel())->shouldNotBeCalled();

        $this->findOneBy($criteria, $caseSensitive)->shouldReturn(null);
    }

    //
    // Get
    //

    public function it_should_get_news_by_id(
        HttpClient $client,
        Container $ioc,
        NewsInterface $news
    ) {
        $id            = 3;
        $criteria      = ['Id' => $id];
        $caseSensitive = true;

        $options = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('News/Get', $options)->shouldBeCalled()->willReturn(
            Helper::contentResponse([
                Helper::fakeNews(['Id' => $id]),
            ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($news);
        $this->get($id)->shouldReturn($news);
    }

    //
    // Has
    //

    public function it_should_return_true_if_news_exist_on_id(
        HttpClient $client,
        Container $ioc,
        NewsInterface $news
    ) {
        $id            = 3;
        $criteria      = ['Id' => $id];
        $caseSensitive = true;

        $options = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('News/Get', $options)->shouldBeCalled()->willReturn(
            Helper::contentResponse([
                Helper::fakeNews(['Id' => $id]),
            ]));

        $ioc->make($this->fqnModel())->shouldBeCalled()->willReturn($news);
        $this->has($id)->shouldReturn(true);
    }

    public function it_should_return_false_if_news_doesnt_exist_on_id(
        HttpClient $client,
        Container $ioc
    ) {
        $id            = 3;
        $criteria      = ['Id' => $id];
        $caseSensitive = true;

        $options = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => 0,
            '$top'    => 1,
        ];

        $client->get('News/Get', $options)
            ->shouldBeCalled()->willReturn(Helper::emptyArrayResponse());

        $ioc->make($this->fqnModel())->shouldNotBeCalled();
        $this->has($id)->shouldReturn(false);
    }
}
