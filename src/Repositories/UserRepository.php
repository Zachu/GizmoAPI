<?php namespace Pisa\GizmoAPI\Repositories;

use Exception;
use Pisa\GizmoAPI\GizmoClient as Client;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /** {@inheritDoc} */
    protected $model = 'User';

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

            $response = $this->client->get('Users/Get', $options);

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $this->makeArray($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to get all users: " . $e->getMessage());
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
        $filter  = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            $response = $this->client->get('Users/Get', $options);
            $response->assertArray();
            $response->assertStatusCodes(200);

            return $this->makeArray($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Finding users by parameters failed. " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     * @uses  findBy
     */
    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $response = $this->findBy($criteria, $caseSensitive, 1);
        if (empty($response)) {
            return false;
        } else {
            return reset($response);
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
            $response = $this->client->get('Users/Get', ['$filter' => 'Id eq ' . $id]);

            // TODO: Do I need to put these everywhere?
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            $body = $response->getBody();
            if (empty($body)) {
                return false;
            } else {
                return $this->make(reset($body));
            }
        } catch (Exception $e) {
            throw new Exception("Getting a user by id failed. " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function has($id)
    {
        try {
            $response = $this->client->get('Users/UserExist', ['userId' => $id]);

            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for user existance failed. " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function hasLoginName($loginName)
    {
        try {
            $response = $this->client->get('Users/LoginNameExist', ['loginName' => $loginName]);
            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for login name existance failed. " . $e->getMessage());
        }

    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function hasUserEmail($userEmail)
    {
        try {
            $response = $this->client->get('Users/UserEmailExist', ['userEmail' => $userEmail]);

            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for user email existance failed. " . $e->getMessage());
        }

    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws Exception on error.
     */
    public function hasUserName($userName)
    {
        try {
            $response = $this->client->get('Users/UserNameExist', ['userName' => $userName]);

            $response->assertBoolean($response);
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for username existance failed. " . $e->getMessage());
        }
    }
}
