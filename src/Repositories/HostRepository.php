<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;

class HostRepository extends BaseRepository implements HostRepositoryInterface
{
    /** @inheritDoc */
    protected $model = 'Host';

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        try {
            $options = ['$skip' => $skip, '$top' => $limit];
            if ($orderBy !== null) {
                $options['$orderby'] = $orderBy;
            }

            $result = $this->client->get('Hosts/Get', $options);
            $this->checkResponseArray($result);
            $this->checkResponseStatusCodes($result, 200);

            return $this->makeArray($result->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to get all hosts: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function findBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        try {
            $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
            $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
            if ($orderBy !== null) {
                $options['$orderby'] = $orderBy;
            }

            $result = $this->client->get('Hosts/Get', $options);
            $this->checkResponseArray($result);
            $this->checkResponseStatusCodes($result, 200);

            return $this->makeArray($result->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to find hosts by parameters: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function get($id)
    {
        try {
            $result = $this->client->get('Hosts/Get/' . (int) $id);
            $this->checkResponseStatusCodes($result, 200);

            $body = $result->getBody();
            if ($body === null) {
                return null;
            } else {
                $this->checkResponseArray($result);
                return $this->make($body);
            }
        } catch (Exception $e) {
            throw new Exception("Getting a host by id failed. " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function getByNumber($hostNumber)
    {
        try {
            $result = $this->client->get('Hosts/GetByNumber', ['hostNumber' => $hostNumber]);

            $this->checkResponseStatusCodes($result, 200);
            $this->checkResponseArray($result);

            return $this->makeArray($result->getBody());
        } catch (Exception $e) {
            throw new Exception("Getting hosts by number failed. " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @uses  get
     */
    public function has($id)
    {
        return ($this->get($id) !== null ? true : false);
    }
}
