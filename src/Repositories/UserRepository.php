<?php namespace Pisa\GizmoAPI\Repositories;

use Exception;
use Pisa\GizmoAPI\GizmoClient as Client;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model = 'UserInterface';

    /**
     * @throws \Exception on error.
     */
    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        // Gather filtering info to options
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $response = $this->client->get('Users/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $this->makeArray($response->getBody());
    }

    /**
     * @throws \Exception on error.
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

        $response = $this->client->get('Users/Get', $options);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $this->makeArray($response->getBody());
    }

    /**
     * @throws \Exception on error.
     * @uses   \Pisa\GizmoAPI\Repositories\UserRepository::findBy()
     */
    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $user = $this->findBy($criteria, $caseSensitive, 1);
        if (empty($user)) {
            return null;
        } else {
            return reset($user);
        }
    }

    /**
     * @throws \Exception on error.
     * @uses   \Pisa\GizmoAPI\Repositories\UserRepository::findOneBy()
     */
    public function get($id)
    {
        return $this->findOneBy(['Id' => (int) $id]);
    }

    /**
     * @throws \Exception on error.
     */
    public function has($id)
    {
        $response = $this->client->get('Users/UserExist', ['userId' => $id]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertBoolean($response);
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error.
     */
    public function hasLoginName($loginName)
    {
        $response = $this->client->get('Users/LoginNameExist', ['loginName' => $loginName]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertBoolean($response);
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error.
     */
    public function hasUserEmail($userEmail)
    {
        $response = $this->client->get('Users/UserEmailExist', ['userEmail' => $userEmail]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertBoolean($response);
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error.
     */
    public function hasUserName($userName)
    {
        $response = $this->client->get('Users/UserNameExist', ['userName' => $userName]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertBoolean($response);
        $response->assertStatusCodes(200);

        return $response->getBody();
    }
}
