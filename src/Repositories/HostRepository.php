<?php namespace Pisa\GizmoAPI\Repositories;

use Exception;

class HostRepository extends BaseRepository implements HostRepositoryInterface
{
    protected $model = 'HostInterface';

    /**
     * @throws Exception on error.
     * @note   $orderBy doesn't work with Id column.
     */
    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Hosts/Get', $options);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $this->makeArray($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to get all hosts: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error.
     * @note   $criteria or $orderBy doesn't work with Id column.
     */
    public function findBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Hosts/Get', $options);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $this->makeArray($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to find hosts by parameters: " . $e->getMessage());
        }
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
        try {
            $response = $this->client->get('Hosts/Get/' . (int) $id);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertStatusCodes(200);

            $body = $response->getBody();
            if ($body === null) {
                return null;
            } else {
                $response->assertArray();
                return $this->make($body);
            }
        } catch (Exception $e) {
            throw new Exception("Getting a host by id failed. " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error.
     */
    public function getByNumber($hostNumber)
    {
        try {
            $response = $this->client->get('Hosts/GetByNumber', ['hostNumber' => $hostNumber]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertStatusCodes(200);
            $response->assertArray();

            return $this->makeArray($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Getting hosts by number failed. " . $e->getMessage());
        }
    }

    /**
     * @uses  get
     */
    public function has($id)
    {
        return ($this->get($id) !== null ? true : false);
    }
}
