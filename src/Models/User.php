<?php namespace Pisa\Api\Gizmo\Models;

use Exception;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;
use Pisa\Api\Gizmo\Repositories\UserRepositoryInterface;

class User extends BaseModel implements BaseModelInterface, UserInterface
{
    protected $fillable = [
        'FirstName',
        'LastName',
        'BirthDate',
        'City',
        'Address',
        'Country',
        'Phone',
        'MobilePhone',
        'PostCode',
        'Sex',
        'Role',
        'IsEnabled',
        'GroupId',
        'Email',
        'UserName',
    ];

    protected $guarded = [
        'Id',
        'Registered',
    ];

    protected $client;

    public function __construct(HttpClient $client, array $attributes = array())
    {
        $this->client = $client;
        $this->load($attributes);
    }

    protected function create()
    {
        try {
            if ($this->exists()) {
                throw new Exception("User already exist. Maybe try update?");
            } else {
                $this->client->post("Users/Create", $this->getAttributes());
            }
        } catch (Exception $e) {
            throw new Exception("Unable to create user: " . $e->getMessage());
        }
    }
    protected function update()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exists. Maybe try create first?");
            } else {
                $this->client->post("Users/Update", $this->getAttributes());
            }
        } catch (Exception $e) {
            throw new Exception("Unable to update user: " . $e->getMessage());
        }
    }

    /**
     * @param $repository UserRepository has to be provided when changing UserName or Email (for checking availability)
     * Otherwise $repository is not needed.
     */
    public function save(UserRepositoryInterface $repository = null)
    {
        try {
            if ($this->exists()) {
                foreach ($this->changed() as $key => $newValue) {
                    if ($key == 'UserName') {
                        if ($repository !== null) {
                            $this->rename($repository, $newValue);
                        } else {
                            throw new Exception("UserRepository not provided when renaming");
                        }
                    } elseif ($key == 'Email') {
                        if ($repository !== null) {
                            $this->setEmail($repository, $newValue);
                        } else {
                            throw new Exception("UserRepository not provided when changing email");
                        }
                    } elseif ($key == 'GroupId') {
                        $this->setUserGroup($newValue);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to save user: " . $e->getMessage());
        }

        parent::save();
    }

    public function delete()
    {
        if (!$this->exists()) {
            throw new Exception("User doesn't exist");
            //@todo error handling
        } elseif ($this->isLoggedIn()) {
            $this->logout();
        }

        $this->client->delete("Users/Delete", [
            'userId' => $this->getPrimaryKeyValue(),
        ]);

        unset($this->Id);
    }

    public function getLoggedInHostId()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $result = $this->client->get('Users/GetLoggedInHost', [
                    'userId' => $this->getPrimaryKeyValue(),
                ])->getBody();

                if (!is_int($result)) {
                    throw new Exception("Requested an integer, got " . gettype($result));
                } elseif ($result === 0) {
                    return false;
                } else {
                    return $result;
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get logged in host: " . $e->getMessage());
        }
    }

    public function isLoggedIn()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $result = $this->client->get('Users/GetLoginState', [
                'userId' => $this->getPrimaryKeyValue(),
            ])->getBody();

            if (!is_bool($result)) {
                throw new Exception("Requested a boolean, got " . gettype($result) . ' ' . $result);
            }

            return (bool) $result;
        } catch (Exception $e) {
            throw new Exception("Unable to get login status: " . $e->getMessage());
        }
    }

    public function lastLoginTime()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $result = $this->client->get('Users/GetLastUserLogin', [
                'userId' => $this->getPrimaryKeyValue(),
            ])->getBody();

            if (strtotime($result) === false) {
                throw new Exception("Requested a valid timestamp, got " . $result);
                //@todo error handling
            }

            return strtotime($result);
        } catch (Exception $e) {
            throw new Exception("Unable to get last login time: " . $e->getMessage());
        }
    }

    public function lastLogoutTime()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $result = $this->client->get('Users/GetLastUserLogout', [
                'userId' => $this->getPrimaryKeyValue(),
            ])->getBody();

            if (strtotime($result) === false) {
                throw new Exception("Requested a valid timestamp, got " . $result);
                //@todo error handling
            }

            return strtotime($result);
        } catch (Exception $e) {
            throw new Exception("Unable to get last logout time: " . $e->getMessage());
        }
    }

    public function login(HostInterface $host)
    {
        try {
            //Currently Gizmo never returns anything but 204, so we never should catch an error
            //That's why we have to do some checks beforehand
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($this->isLoggedIn()) {
                throw new Exception("User is already logged in");
            } elseif (!$host->isFree()) {
                throw new Exception("Someone is already logged in to that host");
            }

            $result = $this->client->post('Users/UserLogin', [
                'userId' => $this->getPrimaryKeyValue(),
                'hostId' => $host->getPrimaryKeyValue(),
            ])->getBody();

            if (!empty($result)) {
                throw new Exception("Didn't expect any results, got " . gettype($result));
            }

            return true;
        } catch (Exception $e) {
            throw new Exception("Unable to log user in: " . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            //Currently Gizmo never returns anything but 204, so we never should catch an error
            //That's why we have to do some checks beforehand
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif (!$this->isLoggedIn()) {
                throw new Exception("User is not logged in anywhere");
            }

            $result = $this->client->post('Users/UserLogout', [
                'userId' => $this->getPrimaryKeyValue(),
            ])->getBody();

            if (!empty($result)) {
                throw new Exception("Didn't expect any results, got " . gettype($result));
            }

            return true;
        } catch (Exception $e) {
            throw new Exception("Unable to log user out: " . $e->getMessage());
        }
    }

    public function rename(UserRepositoryInterface $repository, $newUserName)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($repository->hasUserName($newUserName)) {
                throw new Exception("$newName already exists");
            }

            $result = $this->client->post('Users/Rename', [
                'userId' => $this->getPrimaryKeyValue(),
                'newUserName' => $newUserName,
            ]);
            if ($result->getStatusCode() === 204) {
                return true;
            } else {
                throw new Exception("Unexpected response: " . $result->getStatusCode() . " " . $result->getReasonPhrase());
            }
        } catch (Exception $e) {
            throw new Exception("Unable to rename user: " . $e->getMessage());
        }
    }

    public function setEmail(UserRepositoryInterface $repository, $newEmail)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($repository->hasUserEmail($newEmail)) {
                throw new Exception("$newEmail is already registered");
            }

            $result = $this->client->post('Users/SetUserEmail', [
                'userId' => $this->getPrimaryKeyValue(),
                'newEmail' => $newEmail,
            ]);

            if ($result->getStatusCode() === 204) {
                $this->Email = $newEmail;
                return true;
            } else {
                throw new Exception("Unexpected response: " . $result->getStatusCode() . " " . $result->getReasonPhrase());
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set user email: " . $e->getMessage());
        }
    }

    public function setPassword($newPassword)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $result = $this->client->post('Users/SetUserPassword', [
                'userId' => $this->getPrimaryKeyValue(),
                'newPassword' => $newPassword,
            ]);

            if ($result->getStatusCode() === 204) {
                return true;
            } else {
                throw new Exception("Unexpected response: " . $result->getStatusCode() . " " . $result->getReasonPhrase());
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set user password: " . $e->getMessage());
        }
    }

    public function setUserGroup($groupId)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $groupId = (int) $groupId;

            $result = $this->client->post('Users/SetUserGroup', [
                'userId' => $this->getPrimaryKey(),
                'newUserGroup' => $groupId,
            ]);

            if (!empty($result)) {
                throw new Exception("Didn't expect any results, got " . gettype($result));
            }

            $this->GroupId = $groupId;
            return true;
        } catch (Exception $e) {
            throw new Exception("Unable to set user group: " . $e->getMessage());
        }
    }

    protected function setGroupIdAttribute($group)
    {
        if (is_int($group) || (int) $group != 0) {
            $this->attributes['GroupId'] = (int) $group;
        } else {
            throw new Exception("Could not parse usergroup id from {$group}");
        }
    }

    protected function setBirthDateAttribute($date)
    {
        if (is_int($date)) {
            $return = date('c', $date);
        } elseif (is_string($date) && strtotime($date) !== false) {
            $return = date('c', strtotime($date));
        } else {
            throw new Exception("Could not parse date from {$date}");
        }

        $this->attributes['BirthDate'] = $return;
    }

    protected function getBirthDateAttribute()
    {
        if (!isset($this->attributes['BirthDate'])) {
            return null;
        }

        $date = $this->attributes['BirthDate'];
        if (strtotime($date) !== false) {
            return strtotime($date);
        } else {
            return $date;
        }
    }

    protected function setSexAttribute($sex)
    {
        $male = ['1', 1, 'm', 'male'];
        $female = ['2', 2, 'f', 'female'];

        if (in_array(strtolower($sex), $male)) {
            $return = reset($male); //Assume the first one in the list is the default
        } elseif (in_array(strtolower($sex), $female)) {
            $return = reset($female); //Assume the first one in the list is the default
        } else {
            throw new Exception("Could not parse sex from {$sex}");
        }

        $this->attributes['Sex'] = $return;
    }

    protected function getSexAttribute()
    {
        $male = ['male', 'm', '1', 1];
        $female = ['female', 'f', '2', 2];

        if (!isset($this->attributes['Sex'])) {
            return null;
        } else {
            $sex = $this->attributes['Sex'];
            if (in_array($sex, $male)) {
                return reset($male);
            } elseif (in_array($sex, $female)) {
                return reset($female);
            } else {
                return $sex;
            }
        }
    }

    protected function setIsEnabledAttribute($enabled)
    {
        $this->attributes['IsEnabled'] = $this->toBool($enabled);
    }
}
