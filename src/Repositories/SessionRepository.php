<?php namespace Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Contracts\HttpClient;

class SessionRepository extends BaseRepository implements SessionRepositoryInterface
{
    protected $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception on error
     */
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

    /**
     * @throws Exception on error
     */
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

    /**
     * @throws Exception on error
     */
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

    /**
     * @throws Exception on error
     */
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

    /**
     * @throws Exception on error
     * @uses   findActiveBy This is a wrapper for findActiveBy
     */
    public function findOneActiveBy(array $criteria, $caseSensitive = false)
    {
        $session = $this->findActiveBy($criteria, $caseSensitive, 1);
        if (empty($session)) {
            return null;
        } else {
            return reset($session);
        }
    }

    /**
     * @throws Exception on error
     * @uses findActiveInfosBy This is a wrapper for findActiveInfosBy
     */
    public function findOneActiveInfosBy(array $criteria, $caseSensitive = false)
    {
        $session = $this->findActiveInfosBy($criteria, $caseSensitive, 1);
        if (empty($session)) {
            return null;
        } else {
            return reset($session);
        }
    }

    /**
     * @throws Exception on error
     * @uses   findBy This is a wrapper for findBy
     */
    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $session = $this->findBy($criteria, $caseSensitive, 1);
        if (empty($session)) {
            return null;
        } else {
            return reset($session);
        }
    }

    /**
     * @throws Exception on error
     * @uses   findOneBy This is a wrapper for findOneBy
     */
    public function get($id)
    {
        return $this->findOneBy(['Id' => (int) $id]);
    }

    /**
     * @throws Exception on error
     */
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

    /**
     * @throws Exception on error
     */
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
        return !($this->get($id) === null);
    }

    /**
     * @throws Exception always. You can't make up an session.
     */
    public function make(array $attributes)
    {
        throw new Exception("You can't make up a session");
    }
}
