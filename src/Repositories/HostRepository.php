<?php namespace Pisa\GizmoAPI\Repositories;

use Exception;
use Pisa\GizmoAPI\Exceptions\InternalException;

class HostRepository extends BaseRepository implements HostRepositoryInterface
{
    protected $model = 'HostInterface';

    /**
     * @throws Exception on error.
     * @note   $orderBy doesn't work with Id column.
     */
    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        // Gather filtering info to options
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('Hosts/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $this->makeArray($response->getBody());
    }

    /**
     * @throws Exception on error.
     * @note   $criteria or $orderBy doesn't work with Id column.
     */
    public function findBy(
        array $criteria,
        $caseSensitive = false,
        $limit = 30,
        $skip = 0,
        $orderBy = null
    ) {
        // Gather filtering info to options
        $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('Hosts/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $this->makeArray($response->getBody());
    }

    /**
     * @throws Exception on error.
     * @uses   findBy for searching
     */
    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $host = $this->findBy($criteria, $caseSensitive, 1);
        if (empty($host)) {
            return null;
        } else {
            return reset($host);
        }
    }

    /**
     * @throws Exception on error.
     */
    public function get($id)
    {
        $response = $this->client->get('Hosts/Get/' . (int) $id);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertStatusCodes(200);

        $body = $response->getBody();
        if ($body === null) {
            return null;
        } else {
            $response->assertArray();
            return $this->make($body);
        }
    }

    /**
     * @throws Exception on error.
     */
    public function getByNumber($hostNumber)
    {
        $response = $this->client->get('Hosts/GetByNumber', ['hostNumber' => $hostNumber]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertStatusCodes(200);
        $response->assertArray();

        return $this->makeArray($response->getBody());
    }

    /**
     * @uses  get
     */
    public function has($id)
    {
        return ($this->get($id) !== null ? true : false);
    }
}
