<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;
use Pisa\Api\Gizmo\GizmoClient as Client;
use Pisa\Api\Gizmo\Models\UserInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    protected $model = 'User';

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

    public function delete(UserInterface $user)
    {
        try {
            $result = $this->client->delete('Users/Delete', ['userId' => $user->Id]);
            //@todo check return values
            return true;
        } catch (Exception $e) {
            throw new Exception("Deleting user failed. " . $e->getMessage());
            //@todo error handling
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
            $result = $this->client->get('Users/Get', $options);
            $this->checkResponseArray($result);
            $this->checkResponseStatusCodes($result, 200);

            return $this->makeArray($result->getBody());
        } catch (Exception $e) {
            throw new Exception("Finding users by parameters failed. " . $e->getMessage());
        }
    }

    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $result = $this->findBy($criteria, $caseSensitive, 1);
        if (empty($result)) {
            return false;
        } else {
            return reset($result);
        }
    }

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

    public function save(UserInterface $user)
    {
        if ($user->exists()) {
            try {
                $result = $this->client->post('Users/Update', $user->toArray());

                $this->checkResponseEmpty($result);
                $this->checkResponseStatusCodes($result, 204);

                return true;
            } catch (Exception $e) {
                throw new Exception("Error while updating user. " . $e->getMessage());
            }
        } else {
            // New user
            try {
                $user->Registered = date('c');
                $result           = $this->client->put('Users/Add', $user->toArray());

                $this->checkResponseInteger($result);
                $this->checkResponseStatusCodes($result, 200);
                $user->Id = $result;

                return true;
            } catch (Exception $e) {
                throw new Exception("Error while creating new user. " . $e->getMessage());
                //@todo error handling
            }
        }
    }
}
