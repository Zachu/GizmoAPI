<?php namespace Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Exceptions\InternalException;
use Pisa\GizmoAPI\Exceptions\NotImplementedException;

class SessionRepository extends BaseRepository implements SessionRepositoryInterface
{
    /** @var HttpClient */
    protected $client;

    /**
     * @param HttpClient $client Implemention of http client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Exception on error
     */
    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('Sessions/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function findActiveBy(
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

        $response = $this->client->get('Sessions/GetActive', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function findActiveInfosBy(
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

        $response = $this->client->get('Sessions/GetActiveInfos', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
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

        $response = $this->client->get('Sessions/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }
        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     * @uses   \Pisa\GizmoAPI\Repositories\SessionRepository::findActiveBy()
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
     * @throws \Exception on error
     * @uses \Pisa\GizmoAPI\Repositories\SessionRepository::findActiveInfosBy()
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
     * @throws \Exception on error
     * @uses   \Pisa\GizmoAPI\Repositories\SessionRepository::findBy()
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
     * @throws \Exception on error
     * @uses   \Pisa\GizmoAPI\Repositories\SessionRepository::findOneBy()
     */
    public function get($id)
    {
        return $this->findOneBy(['Id' => (int) $id]);
    }

    /**
     * @throws \Exception on error
     */
    public function getActive($limit = 30, $skip = 0, $orderBy = null)
    {
        // Gather filtering info to options
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('Sessions/GetActive', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }
        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function getActiveInfos($limit = 30, $skip = 0, $orderBy = null)
    {
        // Gather filtering info to options
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('Sessions/GetActiveInfos', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     * @uses   \Pisa\GizmoAPI\Repositories\SessionRepository::get()
     */
    public function has($id)
    {
        return !($this->get($id) === null);
    }

    /**
     * @throws \Exception always. You can't make up an session.
     */
    public function make(array $attributes)
    {
        throw new NotImplementedException("You can't make up a session");
    }
}
