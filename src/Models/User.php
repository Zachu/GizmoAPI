<?php namespace Pisa\GizmoAPI\Models;

use Exception;
use Pisa\GizmoAPI\Repositories\UserRepositoryInterface;

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

    protected $rules = [
        'BirthDate'  => 'date',
        'Registered' => 'date',
    ];

    /**
     * @throws  Exception on error
     */
    public function delete()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($this->isLoggedIn()) {
                $this->logout();
            }

            $response = $this->client->delete("Users/Delete", [
                'userId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);
            unset($this->Id);
            return $this;
        } catch (Exception $e) {
            throw new Exception("Unable to delete user: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function getLoggedInHostId()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $response = $this->client->get('Users/GetLoggedInHost', [
                    'userId' => $this->getPrimaryKeyValue(),
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertInteger();
                $response->assertStatusCodes(200);
                $body = $response->getBody();

                if ($body === 0) {
                    return false;
                } else {
                    return $body;
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get logged in host: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function isLoggedIn()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $response = $this->client->get('Users/GetLoginState', [
                'userId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertInteger(); //Gizmo responses 1 if user is logged in, 0 if not.
            $response->assertStatusCodes(200);

            return (bool) $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Unable to get login status: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function lastLoginTime()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $response = $this->client->get('Users/GetLastUserLogin', [
                'userId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertTime();
            $response->assertStatusCodes(200);

            return strtotime($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to get last login time: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function lastLogoutTime()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $response = $this->client->get('Users/GetLastUserLogout', [
                'userId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertTime();
            $response->assertStatusCodes(200);

            return strtotime($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to get last logout time: " . $e->getMessage());
        }
    }

    /**
     * @return  void
     * @throws  Exception on error
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

            $response = $this->client->post('Users/UserLogin', [
                'userId' => $this->getPrimaryKeyValue(),
                'hostId' => $host->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);
        } catch (Exception $e) {
            throw new Exception("Unable to log user in: " . $e->getMessage());
        }
    }

    /**
     * @return  void
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

            $response = $this->client->post('Users/UserLogout', [
                'userId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);
        } catch (Exception $e) {
            throw new Exception("Unable to log user out: " . $e->getMessage());
        }
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function rename(UserRepositoryInterface $repository, $newUserName)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            } elseif ($repository->hasUserName($newUserName)) {
                throw new Exception("$newUserName already exists");
            }

            $response = $this->client->post('Users/Rename', [
                'userId'      => $this->getPrimaryKeyValue(),
                'newUserName' => $newUserName,
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            $this->UserName = $newUserName;
        } catch (Exception $e) {
            throw new Exception("Unable to rename user: " . $e->getMessage());
        }
    }

    /**
     * @param \Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository UserRepository has to be provided when changing UserName or Email (for checking availability). Otherwise the parameter is not needed
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
     * @return  void
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

            $response = $this->client->post('Users/SetUserEmail', [
                'userId'   => $this->getPrimaryKeyValue(),
                'newEmail' => $newEmail,
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            $this->Email = $newEmail;
        } catch (Exception $e) {
            throw new Exception("Unable to set user email: " . $e->getMessage());
        }
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setPassword($newPassword)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $response = $this->client->post('Users/SetUserPassword', [
                'userId'      => $this->getPrimaryKeyValue(),
                'newPassword' => $newPassword,
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);
        } catch (Exception $e) {
            throw new Exception("Unable to set user password: " . $e->getMessage());
        }
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setUserGroup($groupId)
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exist");
            }

            $groupId = (int) $groupId;

            $response = $this->client->post('Users/SetUserGroup', [
                'userId'       => $this->getPrimaryKeyValue(),
                'newUserGroup' => $groupId,
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            $this->GroupId = $groupId;
        } catch (Exception $e) {
            throw new Exception("Unable to set user group: " . $e->getMessage());
        }
    }

    /**
     * Create a new user instance.
     *
     * @internal  Use $this->save() for really creating a new user.
     * @return User Return $this for chaining.
     */
    protected function create()
    {
        try {
            if ($this->exists()) {
                throw new Exception("User already exist. Maybe try update?");
            } else {
                $response = $this->client->post("Users/Create", $this->getAttributes());
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertEmpty();
                $response->assertStatusCodes(204);

                return $this;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to create user: " . $e->getMessage());
        }
    }

    /**
     * Returns BirthDate as int in all internal usage
     * @internal Used to automatically check that attributes are in a similar shape
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
     * Returns birthdate in 'male' and 'female' format in internal usage.
     * @internal Used to automatically check that attributes are in a similar shape
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
     * Convert BirthDate to ISO 8601 format
     * @param int|string Unix Timestamp or datetime that strtotime understands
     * @internal Used to automatically check that attributes are in a similar shape
     */
    protected function setBirthDateAttribute($date)
    {
        if (is_int($date)) {
            $return = date('c', $date);
        } elseif (is_string($date) && strtotime($date) !== false) {
            $return = date('c', strtotime($date));
        } else {
            $return = null;
        }

        $this->attributes['BirthDate'] = $return;
    }

    /**
     * Convert GroupID to integer
     * @param int $group User GroupID
     * @internal Used to automatically check that attributes are in a similar shape
     */
    protected function setGroupIdAttribute($group)
    {
        if (is_int($group) || (int) $group != 0) {
            $this->attributes['GroupId'] = (int) $group;
        } else {
            $this->attributes['GroupId'] = null;
        }
    }

    /**
     * Sets IsEnabled to boolean
     * @param mixed Some representation of boolean
     * @internal Used to automatically check that attributes are in a similar shape
     */
    protected function setIsEnabledAttribute($enabled)
    {
        $this->attributes['IsEnabled'] = $this->toBool($enabled);
    }

    /**
     * Convert Sex so that male is '1' and female is '2'
     * @param int|string Representation of sex
     * @internal Used to automatically check that attributes are in a similar shape
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
     * Update the host instance.
     *
     * @internal Use $this->save() for really update a user
     * @return Host Return $this for chaining.
     */
    protected function update()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("User doesn't exists. Maybe try create first?");
            } else {
                $response = $this->client->post("Users/Update", $this->getAttributes());
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertEmpty();
                $response->assertStatusCodes(204);

                return $this;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to update user: " . $e->getMessage());
        }
    }
}
