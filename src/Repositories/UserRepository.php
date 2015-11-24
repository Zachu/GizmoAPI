<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;
use Pisa\Api\Gizmo\GizmoClient as Client;

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

            $result = $this->client->get('Users/Get', $options);

            $this->checkResponseArray($result);
            $this->checkResponseStatusCodes($result, 200);

            return $this->makeArray($result->getBody());
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
            $result = $this->client->get('Users/Get', $options);
            $this->checkResponseArray($result);
            $this->checkResponseStatusCodes($result, 200);

            return $this->makeArray($result->getBody());
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
        $result = $this->findBy($criteria, $caseSensitive, 1);
        if (empty($result)) {
            return false;
        } else {
            return reset($result);
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
            $result = $this->client->get('Users/Get', ['$filter' => 'Id eq ' . $id]);
            $this->checkResponseArray($result);
            $this->checkResponseStatusCodes($result, 200);

            $body = $result->getBody();
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
            $result = $this->client->get('Users/UserExist', ['userId' => $id]);

            $this->checkResponseBoolean($result);
            $this->checkResponseStatusCodes($result, 200);

            return $result->getBody();
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
            $result = $this->client->get('Users/LoginNameExist', ['loginName' => $loginName]);
            $this->checkResponseBoolean($result);
            $this->checkResponseStatusCodes($result, 200);

            return $result->getBody();
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
            $result = $this->client->get('Users/UserEmailExist', ['userEmail' => $userEmail]);

            $this->checkResponseBoolean($result);
            $this->checkResponseStatusCodes($result, 200);

            return $result->getBody();
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
            $result = $this->client->get('Users/UserNameExist', ['userName' => $userName]);

            $this->checkResponseBoolean($result);
            $this->checkResponseStatusCodes($result, 200);

            return $result->getBody();
        } catch (Exception $e) {
            throw new Exception("Checking for username existance failed. " . $e->getMessage());
        }
    }
}
