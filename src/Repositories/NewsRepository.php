<?php namespace Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Exceptions\InternalException;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    protected $model = 'NewsInterface';

    /**
     * @throws Exception on error
     * @api
     */
    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        // Gather filtering info to options
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('News/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $this->makeArray($response->getBody());
    }

    /**
     * @throws Exception on error
     * @api
     */
    public function findBy(
        array $criteria,
        $caseSensitive = false,
        $limit = 30,
        $skip = 0,
        $orderBy = null
    ) {
        // Gather filtering info to options
        $options = [
            '$filter' => $this->criteriaToFilter($criteria, $caseSensitive),
            '$skip'   => $skip,
            '$top'    => $limit,
        ];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('News/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $this->makeArray($response->getBody());
    }

    /**
     * @throws Exception on error
     * @uses   findBy This is a wrapper for findBy
     * @api
     */
    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $news = $this->findBy($criteria, $caseSensitive, 1);

        if (empty($news)) {
            return null;
        } else {
            return reset($news);
        }
    }

    /**
     * @throws Exception on error
     * @uses   findBy This is a wrapper for findOneBy
     * @api
     */
    public function get($id)
    {
        return $this->findOneBy(['Id' => (int) $id], true);
    }

    /**
     * @throws Exception on error
     * @uses   findBy This is a wrapper for get
     * @api
     */
    public function has($id)
    {
        return !($this->get($id) === null);
    }
}
