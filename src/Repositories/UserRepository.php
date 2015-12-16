<?php namespace Pisa\GizmoAPI\Repositories;

use Exception;
use Pisa\GizmoAPI\GizmoClient as Client;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model = 'UserInterface';

    /**
     * @throws Exception on error.
     */
    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Users/Get', $options);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $this->makeArray($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to get all users: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error.
     */
    public function findBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Users/Get', $options);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $this->makeArray($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Finding users by parameters failed. " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error.
     * @uses  findBy     This is a wrapper for findBy
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
     * @throws Exception on error.
     * @uses   findOneBy This is a wrapper for findOneBy
     */
    public function get($id)
    {
        return $this->findOneBy(['Id' => (int) $id]);
    }

    /**
     * @throws Exception on error.
     */
    public function has($id)
    {
        try {
            $response = $this->client->get('Users/UserExist', ['userId' => $id]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for user existance failed. " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error.
     */
    public function hasLoginName($loginName)
    {
        try {
            $response = $this->client->get('Users/LoginNameExist', ['loginName' => $loginName]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for login name existance failed. " . $e->getMessage());
        }

    }

    /**
     * @throws Exception on error.
     */
    public function hasUserEmail($userEmail)
    {
        try {
            $response = $this->client->get('Users/UserEmailExist', ['userEmail' => $userEmail]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for user email existance failed. " . $e->getMessage());
        }

    }

    /**
     * @throws Exception on error.
     */
    public function hasUserName($userName)
    {
        try {
            $response = $this->client->get('Users/UserNameExist', ['userName' => $userName]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for username existance failed. " . $e->getMessage());
        }
    }
}
