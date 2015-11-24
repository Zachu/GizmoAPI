<?php namespace Pisa\Api\Gizmo\Models;

use Exception;
use Pisa\Api\Gizmo\Repositories\UserRepositoryInterface;

class User extends BaseModel implements UserInterface
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

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    protected function create()
    {
        try {
            if ($this->exists()) {
                throw new Exception("User already exist. Maybe try update?");
            } else {
                $result = $this->client->post("Users/Create", $this->getAttributes());

                if (is_object($result) && $result->getStatusCode() === 204) {
                    return $this;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result)));
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to create user: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    protected function update()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exists. Maybe try create first?");
            } else {
                $result = $this->client->post("Users/Update", $this->getAttributes());

                if (is_object($result) && $result->getStatusCode() === 204) {
                    return $this;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result)));
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to update user: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @param $repository UserRepository has to be provided when changing UserName or Email (for checking availability). Otherwise the parameter is not needed
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

            return parent::save();
        } catch (Exception $e) {
            throw new Exception("Unable to save user: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
    public function delete()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($this->isLoggedIn()) {
                $this->logout();
            }

            $result = $this->client->delete("Users/Delete", [
                'userId' => $this->getPrimaryKeyValue(),
            ]);

            if (is_object($result) && $result->getStatusCode() === 204) {
                unset($this->Id);
                return $this;
            } else {
                throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
            }

            unset($this->Id);
        } catch (Exception $e) {
            throw new Exception("Unable to delete user: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
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

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
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

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
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

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
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

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     */
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
            ]);

            if (is_object($result) && $result->getStatusCode() === 204) {
                return true;
            } else {
                throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
            }

        } catch (Exception $e) {
            throw new Exception("Unable to log user in: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
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
            ]);

            if (is_object($result) && $result->getStatusCode() === 204) {
                return true;
            } else {
                throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
            }

            return true;
        } catch (Exception $e) {
            throw new Exception("Unable to log user out: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function rename(UserRepositoryInterface $repository, $newUserName)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($repository->hasUserName($newUserName)) {
                throw new Exception("$newName already exists");
            }

            $result = $this->client->post('Users/Rename', [
                'userId'      => $this->getPrimaryKeyValue(),
                'newUserName' => $newUserName,
            ]);
            if (is_object($result) && $result->getStatusCode() === 204) {
                $this->UserName = $newUserName;
                return true;
            } else {
                throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
            }
        } catch (Exception $e) {
            throw new Exception("Unable to rename user: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function setEmail(UserRepositoryInterface $repository, $newEmail)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($repository->hasUserEmail($newEmail)) {
                throw new Exception("$newEmail is already registered");
            }

            $result = $this->client->post('Users/SetUserEmail', [
                'userId'   => $this->getPrimaryKeyValue(),
                'newEmail' => $newEmail,
            ]);

            if (is_object($result) && $result->getStatusCode() === 204) {
                $this->Email = $newEmail;
                return true;
            } else {
                throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set user email: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function setPassword($newPassword)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $result = $this->client->post('Users/SetUserPassword', [
                'userId'      => $this->getPrimaryKeyValue(),
                'newPassword' => $newPassword,
            ]);

            if ($result->getStatusCode() === 204) {
                return true;
            } else {
                throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set user password: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function setUserGroup($groupId)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $groupId = (int) $groupId;

            $result = $this->client->post('Users/SetUserGroup', [
                'userId'       => $this->getPrimaryKeyValue(),
                'newUserGroup' => $groupId,
            ]);

            if (is_object($result) && $result->getStatusCode() === 204) {
                $this->GroupId = $groupId;
                return true;
            } else {
                throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set user group: " . $e->getMessage());
        }
    }

    /**
     * [setGroupIdAttribute description]
     * @param [type] $group [description]
     * @todo documentation
     */
    protected function setGroupIdAttribute($group)
    {
        if (is_int($group) || (int) $group != 0) {
            $this->attributes['GroupId'] = (int) $group;
        } else {
            throw new Exception("Could not parse usergroup id from {$group}");
        }
    }

    /**
     * [setBirthDateAttribute description]
     * @param [type] $date [description]
     * @todo documentation
     */
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

    /**
     * [getBirthDateAttribute description]
     * @return [type] [description]
     * @todo documentation
     */
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

    /**
     * [setSexAttribute description]
     * @param [type] $sex [description]
     * @todo documentation
     */
    protected function setSexAttribute($sex)
    {
        $male   = ['1', 1, 'm', 'male'];
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

    /**
     * [getSexAttribute description]
     * @return [type] [description]
     * @todo documentation
     */
    protected function getSexAttribute()
    {
        $male   = ['male', 'm', '1', 1];
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

    /**
     * [setIsEnabledAttribute description]
     * @param [type] $enabled [description]
     * @todo documentation
     */
    protected function setIsEnabledAttribute($enabled)
    {
        $this->attributes['IsEnabled'] = $this->toBool($enabled);
    }
}
