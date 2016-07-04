# GizmoAPI
[Gizmo Application Management Platform](http://www.gizmopowered.net) API wrapper for PHP.

## Installation

1. Install composer

On Linux / Unix / OSX: `curl -sS https://getcomposer.org/installer | php`

On Windows: https://getcomposer.org/Composer-Setup.exe

Or follow instructions on https://getcomposer.org/doc/00-intro.md

2. Install the package

```
composer require pisa\gizmo-api
```

## Usage

The full API documentation at [wiki](https://github.com/Zachu/GizmoAPI/wiki/ApiIndex)

### Quick usage
```php
<?php
require_once 'vendor/autoload.php';
use Pisa\GizmoAPI\Gizmo;

$gizmo = new Gizmo([
    'http' => [
        'base_uri' => 'http://url_to_gizmo_api_here:8080',
        'auth'     => ['username', 'password'],
    ],
]);

$host = $gizmo->hosts->get(1); // Gets the host model with id 1

$user = $gizmo->users->get(1); // Gets the user model with id 1
```

### Repositories
 - HostRepository: `$gizmo->hosts`
 - NewsRepository: `$gizmo->news`
 - ServiceRepository: `$gizmo->service`
 - SessionRepository: `$gizmo->sessions`
 - UserRepository: `$gizmo->users`

### HostRepository methods

`all([integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Get all model instances from repository

`findBy(array $criteria, [boolean $caseSensitive = false], [integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Finds model instances by parameters

`findOneBy(array $criteria, [boolean $caseSensitive = false])`: Find one model entry by parameters

`get(integer $id)`: Get model by id

`getByNumber(integer $hostNumber)`: Gets hosts by number

`has(integer $id)`: Check if model entry exists.

---
### NewsRepository methods

`all([integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Get all model instances from repository

`findBy(array $criteria, [boolean $caseSensitive = false], [integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Finds model instances by parameters

`findOneBy(array $criteria, [boolean $caseSensitive = false])`: Find one model entry by parameters

`get(integer $id)`: Get model by id

`has(integer $id)`: Check if model entry exists.

---
### ServiceRepository methods

`getTime()`: Returns current system time.

`stop()`: Stops the service

`restart()`: Restarts the service

`getStatus()`: Returns status of the service

`getVersion()`: Returns the service version

`getModule()`: Returns the service module information

`getLicense()`: Returns license information

`getHardwareId()`: Returns hardware id

`getSettings()`: Returns the service settings

---
### SessionRepository methods

`all([integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Get all model instances from repository

`findActiveBy(array $criteria, [boolean $caseSensitive = false], [integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Finds active sessions by criteria

`findActiveInfosBy(array $criteria, [boolean $caseSensitive = false], [integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Find active sessions with additional information by criteria

`findBy(array $criteria, [boolean $caseSensitive = false], [integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Finds model instances by parameters

`findOneActiveBy(array $criteria, [boolean $caseSensitive = false])`: Finds one active session by criteria

`findOneActiveInfosBy(array $criteria, [boolean $caseSensitive = false])`: Find one active session with additional information by criteria

`findOneBy(array $criteria, [boolean $caseSensitive = false])`: Find one model entry by parameters

`get(integer $id)`: Get model by id

`getActive()`: Get all active sessions

`getActiveInfos(array $criteria, boolean $caseSensitive)`: Get all active sessions and additional information

`has(integer $id)`: Check if model entry exists.

`make(array $attributes)`: Make a new model

---
### UserRepository methods

`all([integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Get all model instances from repository

`findBy(array $criteria, [boolean $caseSensitive = false], [integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Finds model instances by parameters

`findOneBy(array $criteria, [boolean $caseSensitive = false])`: Find one model entry by parameters

`get(integer $id)`: Get model by id

`has(integer $id)`: Check if model entry exists.

`hasLoginName(string $loginName)`: Check if user LoginName exists.

`hasUserEmail(string $userEmail)`: Check if user email exists.

`hasUserName(string $userName)`: Check if user username exists.

---
### BaseRepository methods

`make(array $attributes)`: Make a new model

---
### Host methods

`UINotify(string $message, [array $parameters = array ()])`: Send a message dialog to host

`createProcess(array $startInfo)`: Create a new process

`delete()`: This method cannot be used. Host is deleted via the server service

`getLastUserLoginTime()`: Get the time of last user login

`getLastUserLogoutTime()`: Get the time of last user logout

`getProcess(integer $processId)`: Get a single process by its id

`getProcesses([array $criteria = array ()], [boolean $caseSensitive = false], [integer $limit = 30], [integer $skip = 0], [string $orderBy = NULL])`: Get all processes running on the host

`getProcessesByName(string $processName)`: Get all processes running on the host filtered by process name

`isFree()`: Checks if the host is free

`isTurnedOn()`: Shorthand for HasValidDispatcher attribute

`setLockState(boolean $isLocked)`: Set the host to locked state

`setOrderState(boolean $isInOrder)`: Set the host order state

`setSecurityState(boolean $isEnabled)`: Set the host security state

`terminateProcess(array $killInfo)`: Terminate processes

`userLogout()`: {@inheritDoc}

---
### News methods

`delete()`: Delete the model

---
### User methods

`delete()`: Delete the model

`getLoggedInHostId()`: Get the Host id where the user is logged in

`isLoggedIn()`: Check if user is logged in

`lastLoginTime()`: Get the time of last login to a host

`lastLogoutTime()`: Get the time of last logout from a host

`login(\Pisa\GizmoAPI\Models\HostInterface $host)`: Log user in to a host

`logout()`: Log user out from a host

`rename(\Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository, string $newUserName)`: Renames a user

`save([\Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository = NULL])`: Create or update the model

`setEmail(\Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository, string $newEmail)`: Change the user email

`setPassword(string $newPassword)`: Set new password for the user

`setUserGroup(integer $groupId)`: Set user to a new user group

---
### BaseModel methods

`exists()`: Check if model exists.

`getInvalid()`: Return attributes that doesn't pass the validator

`getValidator()`: Return the validator instance

`getRules()`: Returns the current validation rules

`setRules(array $rules)`: Set the validation rules

`mergeRules(array $rules)`: Merge new rules to the current validation rules

`isSaved()`: Check if model has saved all the changes

`validate()`: 

`isValid()`: Check that the model passes validation rules

`load(array $attributes)`: Load model attributes and mark them as saved.

`save()`: Create or update the model

`getPrimaryKeyValue()`: Gets the value of the primary key

`getPrimaryKey()`: Gets the primary key

`fill(array $attributes)`: Set all attributes. Use AttributeMutators if presented.

`getAttribute(string $key)`: Get a single attribute

`getAttributes()`: Get all attributes

`setAttribute(string $key, mixed $value)`: Set a single attribute. Use mutator if presented

`toArray()`: Alias for getAttributes

---

## History
TODO: Write history

## Credits
`Jani "Zachu" Korhonen <jani.korhonen@hel.fi>`