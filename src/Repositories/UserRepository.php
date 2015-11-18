<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;
use Pisa\Api\Gizmo\GizmoClient as Client;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model = 'User';

    /**
     * Fetch list of all users
     * @param  integer $limit   Limit the number of fetched entries. Defaults to 30
     * @param  integer $skip    Skip number of entries (i.e. fetch the next page). Defaults to 0
     * @param  string  $orderBy Column to order the results with
     * @return array            Returns array of Users. Throws Exception on error.
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
     * Finds users by parameters
     * @param  array   $criteria      Array of criteria to search for
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false
     * @param  integer $limit         Limit the number of fetched entries. Defaults to 30
     * @param  integer $skip          Skip number of entries (i.e. fetch the next page). Defaults to 0
     * @param  string  $orderBy       Column to order the results with
     * @return array                  Returns array of Users. Throws Exception on error.
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
     * Find one user by parameters
     * @uses   findBy                 This is wrapper for findBy for searching just one user.
     * @param  array   $criteria      Array of criteria to search for
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false
     * @return User|null              Returns first User found on current criteria. Returns null if none is found. Throws Exception on error.
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
     * Get user by id
     * @param  integer $id Id of the user
     * @return User|null   Returns User. If no user is found, returns null. Throws Exception on error.
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
     * Check if user exists.
     * @param  integer $id Id of the user
     * @return boolean
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
     * Check if user LoginName exists.
     * @param  string $loginName LoginName of the user
     * @return boolean
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
     * Check if user email exists.
     * @param  string $userEmail Email of the user
     * @return boolean
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
     * Check if user username exists.
     * @param  string $userName UserName of the user
     * @return boolean
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
