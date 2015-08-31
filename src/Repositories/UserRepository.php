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

            $result = $this->client->get('Users/Get', $options)->getBody();
            if (is_array($result)) {
                $users = $this->makeArray($result);
            } else {
                throw new Exception("Requesting array of users, got " . gettype($result));
                //@todo error handling
            }

            return $users;
        } catch (Exception $e) {
            throw new Exception("Unable to get all users: " . $e->getMessage());
        }
    }

    public function delete(UserInterface $user)
    {
        try {
            $result = $this->client->request('Users/Delete', ['userId' => $user->Id], 'DELETE');
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

        $result = $this->client->request('Users/Get', $options);
        //@todo error handling
        //@todo check return values

        return $this->makeArray($result);
    }

    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $filter = $this->criteriaToFilter($criteria, $caseSensitive);
        $result = $this->client->request('Users/Get', ['$filter' => $filter, '$top' => 1]);

        if (!is_array($result)) {
            throw new Exception("Requesting array of users, got " . gettype($result));
            //@todo error handling
        } elseif (empty($result)) {
            return false;
        } else {
            return $this->make(reset($result));
        }
    }

    public function get($id)
    {
        $result = $this->client->request('Users/Get', ['$filter' => 'Id eq ' . $id]);

        if (is_array($result) && !isset($result[0])) {
            return false;
        } elseif (!is_array($result)) {
            throw new Exception("Requesting array of users, got " . gettype($result));
            //@todo error handling
        }

        return $this->make($result[0]);
    }

    public function has($id)
    {
        $result = $this->client->request('Users/UserExist', ['userId' => $id]);

        if (!is_bool($result)) {
            throw new Exception("Requesting boolean, got " . gettype($result));
            //@todo error handling
        }

        return $result;
    }

    public function hasUserName($userName)
    {
        $result = $this->client->request('Users/UserNameExist', ['userName' => $userName]);

        if (!is_bool($result)) {
            throw new Exception("Requesting boolean, got " . gettype($result));
            //@todo error handling
        }

        return $result;
    }
    public function hasUserEmail($userEmail)
    {
        $result = $this->client->request('Users/UserEmailExist', ['userEmail' => $userEmail]);

        if (!is_bool($result)) {
            throw new Exception("Requesting boolean, got " . gettype($result));
            //@todo error handling
        }

        return $result;
    }
    public function hasLoginName($loginName)
    {
        $result = $this->client->request('Users/LoginNameExist', ['loginName' => $loginName]);

        if (!is_bool($result)) {
            throw new Exception("Requesting boolean, got " . gettype($result));
            //@todo error handling
        }

        return $result;
    }

    public function save(UserInterface $user)
    {
        if ($user->exists()) {
            try {
                $result = $this->client->request('Users/Update', $user->toArray(), 'POST');
                if (empty($result)) {
                    return true;
                } else {
                    throw new Exception("Didn't expect any results, got " . gettype($result));
                    //@todo error handling
                }
            } catch (Exception $e) {
                //@todo error handling
                throw new Exception("Error while updating user. " . $e->getMessage());
            }
        } else {
            // New user
            try {
                $user->Registered = date('c');
                $result           = $this->client->request('Users/Add', $user->toArray(), 'PUT');
                if (is_int($result)) {
                    $user->Id = $result;
                } else {
                    throw new Exception("Expecting integer user id, got " . gettype($result));
                    //@todo error handling
                }
            } catch (Exception $e) {
                throw new Exception("Error while creating new user. " . $e->getMessage());
                //@todo error handling
            }
        }
    }
}
