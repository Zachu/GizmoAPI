<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;

class HostRepository extends BaseRepository implements HostRepositoryInterface
{
    protected $model = 'Host';

    /**
     * Fetch list of all hosts.
     * @param  integer $limit   Limit the number of fetched entries. Defaults to 30
     * @param  integer $skip    Skip number of entries (i.e. fetch the next page). Defaults to 0
     * @param  string  $orderBy Column to order the results with
     * @return array            Returns array of hosts. Throws Exception on error.
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
     * Finds hosts by parameters
     * @param  array   $criteria      Array of criteria to search for
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false
     * @param  integer $limit         Limit the number of fetched entries. Defaults to 30
     * @param  integer $skip          Skip number of entries (i.e. fetch the next page). Defaults to 0
     * @param  string  $orderBy       Column to order the results with
     * @return array                  Returns array of hosts. Throws Exception on error.
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
     * Find one host by parameters
     * @uses   findBy                 This is wrapper for findBy for searching just one host.
     * @param  array   $criteria      Array of criteria to search for
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false
     * @return Host|null              Returns first Host found on current criteria. Returns null if none is found. Throws Exception on error.
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
     * Get host by id
     * @param  integer $id Id of the host
     * @return Host|null   Returns Host. If no host is found, returns null. Throws Exception on error.
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
     * Get all hosts by number
     * @param  integer $hostNumber Number of hosts to search for
     * @return array               Returns array of hosts. Throws Exception on error.
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
     * Check if host exists.
     * @param  [type]  $id [description]
     * @return boolean     [description]
     * @uses   get         This is a wrapper for get to check user existance.
     */
    public function has($id)
    {
        return ($this->get($id) !== null ? true : false);
    }
}
