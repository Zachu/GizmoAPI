<?php namespace Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Contracts\HttpClient;

class SessionRepository extends BaseRepository implements SessionRepositoryInterface
{
    protected $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Sessions/Get', $options);

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Unable to get all sessions: " . $e->getMessage());
        }
    }

    public function findActiveBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Sessions/GetActive', $options);

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Unable to find active sessions: " . $e->getMessage());
        }
    }

    public function findActiveInfosBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Sessions/GetActiveInfos', $options);

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Unable to find active session infos: " . $e->getMessage());
        }
    }

    public function findBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Sessions/Get', $options);

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Unable to find sessions: " . $e->getMessage());
        }
    }

    public function findOneActiveBy(array $criteria, $caseSensitive = false)
    {
        $session = $this->findActiveBy($criteria, $caseSensitive, 1);
        if (empty($session)) {
            return false;
        } else {
            return reset($session);
        }
    }

    public function findOneActiveInfosBy(array $criteria, $caseSensitive = false)
    {
        $session = $this->findActiveInfosBy($criteria, $caseSensitive, 1);
        if (empty($session)) {
            return false;
        } else {
            return reset($session);
        }
    }

    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $session = $this->findBy($criteria, $caseSensitive, 1);
        if (empty($session)) {
            return false;
        } else {
            return reset($session);
        }
    }

    /**
     * @throws Exception on error
     * @uses   findBy This is a wrapper for findOneBy
     * @api
     */
    public function get($id)
    {
        return $this->findOneBy(['Id' => (int) $id]);
    }

    public function getActive($limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Sessions/GetActive', $options);

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Unable to get all active sessions: " . $e->getMessage());
        }
    }

    public function getActiveInfos($limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Sessions/GetActiveInfos', $options);

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Unable to get all active session infos: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     * @uses   findBy This is a wrapper for get
     * @api
     */
    public function has($id)
    {
        return !($this->get($id) === false);
    }

    public function make(array $attributes)
    {
        throw new Exception("You can't make up a session");
    }
}
