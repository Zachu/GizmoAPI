<?php namespace Pisa\GizmoAPI\Models;

use Pisa\GizmoAPI\Exceptions\InternalException;
use Pisa\GizmoAPI\Exceptions\ValidationException;
use Pisa\GizmoAPI\Exceptions\RequirementException;
use Pisa\GizmoAPI\Exceptions\InvalidArgumentException;
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
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        } elseif ($this->isLoggedIn()) {
            $this->logout();
        }

        $this->logger->notice("[User $this] Deleting user");
        $response = $this->client->delete("Users/Delete", [
            'userId' => $this->getPrimaryKeyValue(),
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);
        unset($this->Id);
        return $this;
    }

    /**
     * @throws  Exception on error
     */
    public function getLoggedInHostId()
    {
        if ($this->exists() === false) {
            throw new RequirementException("Model does not exist");
        } else {
            $response = $this->client->get('Users/GetLoggedInHost', [
                'userId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new InternalException("Response failed");
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
    }

    /**
     * @throws  Exception on error
     */
    public function isLoggedIn()
    {
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        }

        $response = $this->client->get('Users/GetLoginState', [
            'userId' => $this->getPrimaryKeyValue(),
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertInteger(); //Gizmo responses 1 if user is logged in, 0 if not.
        $response->assertStatusCodes(200);

        return (bool) $response->getBody();
    }

    /**
     * @throws  Exception on error
     */
    public function lastLoginTime()
    {
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        }

        $response = $this->client->get('Users/GetLastUserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertTime();
        $response->assertStatusCodes(200);

        return strtotime($response->getBody());
    }

    /**
     * @throws  Exception on error
     */
    public function lastLogoutTime()
    {
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        }

        $response = $this->client->get('Users/GetLastUserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertTime();
        $response->assertStatusCodes(200);

        return strtotime($response->getBody());
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function login(HostInterface $host)
    {
        //Currently Gizmo never returns anything but 204, so we never should catch an error
        //That's why we have to do some checks beforehand
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        } elseif ($this->isLoggedIn()) {
            throw new RequirementException("User is already logged in");
        } elseif (!$host->isFree()) {
            throw new RequirementException("Someone is already logged in to that host");
        }

        $this->logger->notice("[User $this] Logging user in to $host");
        $response = $this->client->post('Users/UserLogin', [
            'userId' => $this->getPrimaryKeyValue(),
            'hostId' => $host->getPrimaryKeyValue(),
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function logout()
    {
        //Currently Gizmo never returns anything but 204, so we never should catch an error
        //That's why we have to do some checks beforehand
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        } elseif (!$this->isLoggedIn()) {
            throw new RequirementException("User is not logged in anywhere");
        }

        $this->logger->notice("[User $this] Logging user out");
        $response = $this->client->post('Users/UserLogout', [
            'userId' => $this->getPrimaryKeyValue(),
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function rename(UserRepositoryInterface $repository, $newUserName)
    {
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        } elseif ($repository->hasUserName($newUserName)) {
            throw new ValidationException("$newUserName already exists");
        }

        $this->logger->info("[User $this] Renaming to $newUserName");
        $response = $this->client->post('Users/Rename', [
            'userId'      => $this->getPrimaryKeyValue(),
            'newUserName' => $newUserName,
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);

        $this->UserName = $newUserName;
    }

    /**
     * A shorthand for setPassword('')
     *
     * @uses $this->setPassword()
     */
    public function resetPassword()
    {
        return $this->setPassword('');
    }

    /**
     * @param \Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository UserRepository has to be provided when changing UserName or Email (for checking availability). Otherwise the parameter is not needed
     */
    public function save(UserRepositoryInterface $repository = null)
    {
        if ($this->exists()) {
            foreach ($this->changed() as $key => $newValue) {
                if ($key == 'UserName') {
                    if ($repository !== null) {
                        $this->rename($repository, $newValue);
                    } else {
                        throw new InvalidArgumentException(
                            "UserRepository not provided when renaming"
                        );
                    }
                } elseif ($key == 'Email') {
                    if ($repository !== null) {
                        $this->setEmail($repository, $newValue);
                    } else {
                        throw new InvalidArgumentException(
                            "UserRepository not provided when changing email"
                        );
                    }
                } elseif ($key == 'GroupId') {
                    $this->setUserGroup($newValue);
                }
            }
        }

        return parent::save();
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setEmail(UserRepositoryInterface $repository, $newEmail)
    {
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        } elseif ($repository->hasUserEmail($newEmail)) {
            throw new ValidationException("$newEmail is already registered");
        }

        $this->logger->info("[User $this] Changing email to $newEmail");
        $response = $this->client->post('Users/SetUserEmail', [
            'userId'   => $this->getPrimaryKeyValue(),
            'newEmail' => $newEmail,
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);

        $this->Email = $newEmail;
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setPassword($newPassword)
    {
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        }

        $this->logger->info("[User $this] Changing password");
        $response = $this->client->post('Users/SetUserPassword', [
            'userId'      => $this->getPrimaryKeyValue(),
            'newPassword' => $newPassword,
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setUserGroup($groupId)
    {
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exist");
        }

        $groupId = (int) $groupId;

        $this->logger->info("[User $this] Changing user group to $groupId");
        $response = $this->client->post('Users/SetUserGroup', [
            'userId'       => $this->getPrimaryKeyValue(),
            'newUserGroup' => $groupId,
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);

        $this->GroupId = $groupId;
    }

    /**
     * Create a new user instance.
     *
     * @internal  Use $this->save() for really creating a new user.
     * @return User Return $this for chaining.
     */
    protected function create()
    {
        if ($this->exists()) {
            throw new RequirementException("User already exist. Maybe try update?");
        } else {
            $this->logger->notice("[User $this] Creating a new user");
            $response = $this->client->post("Users/Create", $this->getAttributes());
            if ($response === null) {
                throw new InternalException("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            return $this;
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
            throw new InvalidArgumentException("Could not parse sex from {$sex}");
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
        if (!$this->exists()) {
            throw new RequirementException("User doesn't exists. Maybe try create first?");
        } else {
            $response = $this->client->post("Users/Update", $this->getAttributes());
            if ($response === null) {
                throw new InternalException("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            return $this;
        }
    }

    public function __toString()
    {
        if ($this->UserName) {
            return 'User[' . $this->UserName . ']';
        } else {
            return parent::__toString();
        }
    }

}
