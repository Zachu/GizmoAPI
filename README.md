Developing
==========
Requirements
------------
```sudo apt-get install php5 git nodejs npm```

Setting up composer
-------------------
```curl -sS https://getcomposer.org/installer | php```

```sudo mv composer.phar /usr/local/bin/composer```

Setting up gulp
---------------
```sudo npm install -g gulp```

Clone the repository
--------------------
```git clone git@gitlab.pelitalo.local:Zachu/GizmoAPI.git```

Install packages
----------------
```npm install```
```composer update```

# API Index

## Table of Contents

* [GuzzleClientAdapter](#guzzleclientadapter)
    * [__construct](#__construct)
    * [delete](#delete)
    * [get](#get)
    * [post](#post)
    * [put](#put)
    * [request](#request)
* [GuzzleResponseAdapter](#guzzleresponseadapter)
    * [__construct](#__construct-1)
    * [getHeaders](#getheaders)
    * [getBody](#getbody)
    * [getString](#getstring)
    * [getJson](#getjson)
    * [getStatusCode](#getstatuscode)
    * [getReasonPhrase](#getreasonphrase)
    * [getType](#gettype)
    * [__toString](#__tostring)
* [IlluminateContainerAdapter](#illuminatecontaineradapter)
    * [__construct](#__construct-2)
    * [bind](#bind)
    * [make](#make)
    * [singleton](#singleton)
* [Gizmo](#gizmo)
    * [__construct](#__construct-3)
    * [getConfig](#getconfig)
    * [setConfig](#setconfig)
    * [__get](#__get)
    * [hasRepository](#hasrepository)
    * [getRepository](#getrepository)
* [Host](#host)
    * [UINotify](#uinotify)
    * [createProcess](#createprocess)
    * [delete](#delete-1)
    * [getLastUserLoginTime](#getlastuserlogintime)
    * [getLastUserLogoutTime](#getlastuserlogouttime)
    * [getProcess](#getprocess)
    * [getProcesses](#getprocesses)
    * [getProcessesByName](#getprocessesbyname)
    * [isFree](#isfree)
    * [setLockState](#setlockstate)
    * [setOrderState](#setorderstate)
    * [setSecurityState](#setsecuritystate)
    * [terminateProcess](#terminateprocess)
    * [userLogout](#userlogout)
    * [__construct](#__construct-4)
    * [exists](#exists)
    * [isSaved](#issaved)
    * [load](#load)
    * [save](#save)
    * [fill](#fill)
    * [getAttributes](#getattributes)
    * [getAttribute](#getattribute)
    * [setAttribute](#setattribute)
    * [toArray](#toarray)
    * [getPrimaryKeyValue](#getprimarykeyvalue)
    * [getPrimaryKey](#getprimarykey)
* [User](#user)
    * [delete](#delete-2)
    * [getLoggedInHostId](#getloggedinhostid)
    * [isLoggedIn](#isloggedin)
    * [lastLoginTime](#lastlogintime)
    * [lastLogoutTime](#lastlogouttime)
    * [login](#login)
    * [logout](#logout)
    * [rename](#rename)
    * [save](#save-1)
    * [setEmail](#setemail)
    * [setPassword](#setpassword)
    * [setUserGroup](#setusergroup)
    * [__construct](#__construct-5)
    * [exists](#exists-1)
    * [isSaved](#issaved-1)
    * [load](#load-1)
    * [fill](#fill-1)
    * [getAttributes](#getattributes-1)
    * [getAttribute](#getattribute-1)
    * [setAttribute](#setattribute-1)
    * [toArray](#toarray-1)
    * [getPrimaryKeyValue](#getprimarykeyvalue-1)
    * [getPrimaryKey](#getprimarykey-1)
* [HostRepository](#hostrepository)
    * [all](#all)
    * [findBy](#findby)
    * [findOneBy](#findoneby)
    * [get](#get-1)
    * [getByNumber](#getbynumber)
    * [has](#has)
    * [__construct](#__construct-6)
    * [criteriaToFilter](#criteriatofilter)
    * [fqnModel](#fqnmodel)
    * [make](#make-1)
* [NewsRepository](#newsrepository)
    * [all](#all-1)
    * [findBy](#findby-1)
    * [findOneBy](#findoneby-1)
    * [get](#get-2)
    * [has](#has-1)
    * [make](#make-2)
* [SessionsRepository](#sessionsrepository)
    * [all](#all-2)
    * [findBy](#findby-2)
    * [findOneBy](#findoneby-2)
    * [get](#get-3)
    * [has](#has-2)
    * [make](#make-3)
* [UserRepository](#userrepository)
    * [all](#all-3)
    * [findBy](#findby-3)
    * [findOneBy](#findoneby-3)
    * [get](#get-4)
    * [has](#has-3)
    * [hasLoginName](#hasloginname)
    * [hasUserEmail](#hasuseremail)
    * [hasUserName](#hasusername)
    * [__construct](#__construct-7)
    * [criteriaToFilter](#criteriatofilter-1)
    * [fqnModel](#fqnmodel-1)
    * [make](#make-4)

## GuzzleClientAdapter





* Full name: Pisa\GizmoAPI\Adapters\GuzzleClientAdapter
* This class implements: Pisa\GizmoAPI\Contracts\HttpClient





### __construct

Create a new response

```php
GuzzleClientAdapter::__construct( \GuzzleHttp\ClientInterface|null $client )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $client | **\GuzzleHttp\ClientInterface&#124;null** | If no client is given, one is created automatically |




---


### delete

Perform a HTTP DELETE request

```php
GuzzleClientAdapter::delete( string $url, array $parameters, array $options )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $url | **string** |  |
| $parameters | **array** |  |
| $options | **array** |  |




---


### get

Perform a HTTP GET request

```php
GuzzleClientAdapter::get( string $url, array $parameters, array $options )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $url | **string** |  |
| $parameters | **array** |  |
| $options | **array** |  |




---


### post

Perform a HTTP POST request

```php
GuzzleClientAdapter::post( string $url, array $parameters, array $options )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $url | **string** |  |
| $parameters | **array** |  |
| $options | **array** |  |




---


### put

Perform a HTTP PUT request

```php
GuzzleClientAdapter::put( string $url, array $parameters, array $options )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $url | **string** |  |
| $parameters | **array** |  |
| $options | **array** |  |




---


### request

Perform a custom HTTP request

```php
GuzzleClientAdapter::request( string $method, string $url, array $parameters, array $options )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $method | **string** |  |
| $url | **string** |  |
| $parameters | **array** |  |
| $options | **array** |  |




---

## GuzzleResponseAdapter





* Full name: Pisa\GizmoAPI\Adapters\GuzzleResponseAdapter
* This class implements: Pisa\GizmoAPI\Contracts\HttpResponse





### __construct



```php
GuzzleResponseAdapter::__construct( \GuzzleHttp\Psr7\Response $response )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $response | **\GuzzleHttp\Psr7\Response** |  |




---


### getHeaders

Get the response headers

```php
GuzzleResponseAdapter::getHeaders(  )
```








---


### getBody

Gets the response body

```php
GuzzleResponseAdapter::getBody( boolean $autodetect )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $autodetect | **boolean** |  |




---


### getString

Get the body as a string

```php
GuzzleResponseAdapter::getString(  )
```








---


### getJson

Get JSON body

```php
GuzzleResponseAdapter::getJson(  )
```








---


### getStatusCode

Get the http status code

```php
GuzzleResponseAdapter::getStatusCode(  )
```








---


### getReasonPhrase

Get the reason phrase for the according status code

```php
GuzzleResponseAdapter::getReasonPhrase(  )
```








---


### getType

Get the content type

```php
GuzzleResponseAdapter::getType(  )
```








---


### __toString



```php
GuzzleResponseAdapter::__toString(  )
```








---

## IlluminateContainerAdapter

Illuminate Container Adapter



* Full name: Pisa\GizmoAPI\Adapters\IlluminateContainerAdapter
* This class implements: Pisa\GizmoAPI\Contracts\Container





### __construct

Create a container

```php
IlluminateContainerAdapter::__construct( \Illuminate\Contracts\Container\Container|null $container )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $container | **\Illuminate\Contracts\Container\Container&#124;null** | If no container is given, one is created automatically |




---


### bind

Register a binding with the container

```php
IlluminateContainerAdapter::bind( string|array $abstract, \Closure|string|null $concrete, boolean $shared )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $abstract | **string&#124;array** |  |
| $concrete | **\Closure&#124;string&#124;null** |  |
| $shared | **boolean** |  |




---


### make

Resolve a binding.

```php
IlluminateContainerAdapter::make( string $abstract, array $parameters )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $abstract | **string** |  |
| $parameters | **array** |  |




---


### singleton

Register a shared binding with the container

```php
IlluminateContainerAdapter::singleton( string|array $abstract, \Closure|string|null $concrete )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $abstract | **string&#124;array** |  |
| $concrete | **\Closure&#124;string&#124;null** |  |




---

## Gizmo





* Full name: Pisa\GizmoAPI\Gizmo






### __construct



```php
Gizmo::__construct( array $config, \Pisa\GizmoAPI\Contracts\Container $ioc )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $config | **array** |  |
| $ioc | **\Pisa\GizmoAPI\Contracts\Container** |  |




---


### getConfig



```php
Gizmo::getConfig( $name )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | **** |  |




---


### setConfig



```php
Gizmo::setConfig( $name, $value )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | **** |  |
| $value | **** |  |




---


### __get



```php
Gizmo::__get( $name )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | **** |  |




---


### hasRepository



```php
Gizmo::hasRepository( $name )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | **** |  |




---


### getRepository



```php
Gizmo::getRepository( $name )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | **** |  |




---

## Host





* Full name: Pisa\GizmoAPI\Models\Host
* Parent class: Pisa\GizmoAPI\Models\BaseModel
* This class implements: Pisa\GizmoAPI\Models\HostInterface





### UINotify

Send a message dialog to host

```php
Host::UINotify( string $message, array $parameters ): boolean
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $message | **string** |  |
| $parameters | **array** |  |


**Return Value:**

If ShowDialog is set to true, returns true if user clicks ok, false if user clicks cancel.


**See Also:**

* \Pisa\GizmoAPI\Models\$this-&gt;defaultParameters - for parameters to modify

---


### createProcess

Create a new process

```php
Host::createProcess( array $startInfo )
```

Example:
<code>
$this->createProcess(['FileName' => 'C:\Start.bat']);
</code>





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $startInfo | **array** |  |




---


### delete

Delete the model

```php
HostInterface::delete(  ): \Pisa\GizmoAPI\Models\BaseModel
```






**Return Value:**

Return $this for chaining.



---


### getLastUserLoginTime

Get the time of last user login

```php
Host::getLastUserLoginTime(  )
```








---


### getLastUserLogoutTime

Get the time of last user logout

```php
Host::getLastUserLogoutTime(  )
```








---


### getProcess

Get a single process by its id

```php
Host::getProcess( integer $processId )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $processId | **integer** |  |




---


### getProcesses

Get all processes running on the host

```php
Host::getProcesses(  )
```








---


### getProcessesByName

Get all processes running on the host filtered by process name

```php
Host::getProcessesByName( string $processName )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $processName | **string** |  |




---


### isFree

Checks if the host is free

```php
Host::isFree(  )
```








---


### setLockState

Set the host to locked state

```php
Host::setLockState( boolean $isLocked )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $isLocked | **boolean** |  |




---


### setOrderState

Set the host order state

```php
Host::setOrderState( $isOutOfOrder )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $isOutOfOrder | **** |  |




---


### setSecurityState

Set the host security state

```php
Host::setSecurityState( boolean $isEnabled )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $isEnabled | **boolean** |  |




---


### terminateProcess

Terminate processes

```php
Host::terminateProcess( array $killInfo )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $killInfo | **array** |  |




---


### userLogout

Logs user out from the host

```php
Host::userLogout(  )
```








---


### __construct

Make a new model instance

```php
Host::__construct( \Pisa\GizmoAPI\Contracts\HttpClient $client, array $attributes )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $client | **\Pisa\GizmoAPI\Contracts\HttpClient** | HTTP client |
| $attributes | **array** | Attributes to initialize |




---


### exists

Check if model exists (has a primary key)

```php
HostInterface::exists(  ): boolean
```








---


### isSaved

Check if model has saved all the changes

```php
HostInterface::isSaved(  ): boolean
```








---


### load

Load model attributes and mark them as saved.

```php
Host::load( array $attributes ): void
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** | Attributes to be loaded |




---


### save

Create or update the model

```php
HostInterface::save(  ): \Pisa\GizmoAPI\Models\BaseModel
```






**Return Value:**

Return $this for chaining



---


### fill

Set all attributes. Use AttributeMutators if presented.

```php
HostInterface::fill( array $attributes ): void
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** |  |




---


### getAttributes

Get all attributes

```php
HostInterface::getAttributes(  ): array
```








---


### getAttribute

Get a single attribute

```php
HostInterface::getAttribute( string $key ): mixed
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $key | **string** |  |


**Return Value:**

Attribute value



---


### setAttribute

Set a single attribute. Use mutator if presented

```php
HostInterface::setAttribute( string $key, mixed $value )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $key | **string** |  |
| $value | **mixed** |  |




---


### toArray

Alias for getAttributes

```php
HostInterface::toArray(  )
```








---


### getPrimaryKeyValue

Gets the value of the primary key

```php
HostInterface::getPrimaryKeyValue(  ): mixed
```








---


### getPrimaryKey

Gets the primary key

```php
HostInterface::getPrimaryKey(  ): string
```








---

## User





* Full name: Pisa\GizmoAPI\Models\User
* Parent class: Pisa\GizmoAPI\Models\BaseModel
* This class implements: Pisa\GizmoAPI\Models\UserInterface





### delete

Delete the model

```php
UserInterface::delete(  ): \Pisa\GizmoAPI\Models\BaseModel
```






**Return Value:**

Return $this for chaining.



---


### getLoggedInHostId

Get the Host id where the user is logged in

```php
User::getLoggedInHostId(  )
```








---


### isLoggedIn

Check if user is logged in

```php
User::isLoggedIn(  )
```








---


### lastLoginTime

Get the time of last login to a host

```php
User::lastLoginTime(  )
```








---


### lastLogoutTime

Get the time of last logout from a host

```php
User::lastLogoutTime(  )
```








---


### login

Log user in to a host

```php
User::login( \Pisa\GizmoAPI\Models\HostInterface $host )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $host | **\Pisa\GizmoAPI\Models\HostInterface** |  |




---


### logout

Log user out from a host

```php
User::logout(  )
```








---


### rename

Renames a user

```php
User::rename( \Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository, string $newUserName )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $repository | **\Pisa\GizmoAPI\Repositories\UserRepositoryInterface** |  |
| $newUserName | **string** |  |




---


### save

Create or update the model

```php
UserInterface::save(  ): \Pisa\GizmoAPI\Models\BaseModel
```






**Return Value:**

Return $this for chaining



---


### setEmail

Change the user email

```php
User::setEmail( \Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository, string $newEmail )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $repository | **\Pisa\GizmoAPI\Repositories\UserRepositoryInterface** |  |
| $newEmail | **string** |  |




---


### setPassword

Set new password for the user

```php
User::setPassword( string $newPassword )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $newPassword | **string** |  |




---


### setUserGroup

Set user to a new user group

```php
User::setUserGroup( integer $groupId )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $groupId | **integer** |  |




---


### __construct

Make a new model instance

```php
User::__construct( \Pisa\GizmoAPI\Contracts\HttpClient $client, array $attributes )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $client | **\Pisa\GizmoAPI\Contracts\HttpClient** | HTTP client |
| $attributes | **array** | Attributes to initialize |




---


### exists

Check if model exists (has a primary key)

```php
UserInterface::exists(  ): boolean
```








---


### isSaved

Check if model has saved all the changes

```php
UserInterface::isSaved(  ): boolean
```








---


### load

Load model attributes and mark them as saved.

```php
User::load( array $attributes ): void
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** | Attributes to be loaded |




---


### fill

Set all attributes. Use AttributeMutators if presented.

```php
UserInterface::fill( array $attributes ): void
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** |  |




---


### getAttributes

Get all attributes

```php
UserInterface::getAttributes(  ): array
```








---


### getAttribute

Get a single attribute

```php
UserInterface::getAttribute( string $key ): mixed
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $key | **string** |  |


**Return Value:**

Attribute value



---


### setAttribute

Set a single attribute. Use mutator if presented

```php
UserInterface::setAttribute( string $key, mixed $value )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $key | **string** |  |
| $value | **mixed** |  |




---


### toArray

Alias for getAttributes

```php
UserInterface::toArray(  )
```








---


### getPrimaryKeyValue

Gets the value of the primary key

```php
UserInterface::getPrimaryKeyValue(  ): mixed
```








---


### getPrimaryKey

Gets the primary key

```php
UserInterface::getPrimaryKey(  ): string
```








---

## HostRepository





* Full name: Pisa\GizmoAPI\Repositories\HostRepository
* Parent class: Pisa\GizmoAPI\Repositories\BaseRepository
* This class implements: Pisa\GizmoAPI\Repositories\HostRepositoryInterface





### all

Get all model instances from repository

```php
HostRepositoryInterface::all( integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances.



---


### findBy

Finds model instances by parameters

```php
HostRepositoryInterface::findBy( array $criteria, boolean $caseSensitive, integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for. |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false. |
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances. Throws Exception on error.



---


### findOneBy

Find one model entry by parameters

```php
HostRepositoryInterface::findOneBy( array $criteria, boolean $caseSensitive ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false |


**Return Value:**

Returns the first model entry found on current criteria. Returns null if none is found. Throws Exception on error.



---


### get

Get model by id

```php
HostRepositoryInterface::get( integer $id ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |


**Return Value:**

Returns model. If no model is found, returns null. Throws Exception on error.



---


### getByNumber

Gets hosts by number

```php
HostRepository::getByNumber( integer $hostNumber )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $hostNumber | **integer** |  |




---


### has

Check if model entry exists.

```php
HostRepositoryInterface::has( integer $id ): boolean
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |




---


### __construct



```php
HostRepository::__construct( \Pisa\GizmoAPI\Contracts\Container $ioc, \Pisa\GizmoAPI\Contracts\HttpClient $client )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $ioc | **\Pisa\GizmoAPI\Contracts\Container** |  |
| $client | **\Pisa\GizmoAPI\Contracts\HttpClient** |  |




---


### criteriaToFilter

Turn array of criteria into an OData filter

```php
HostRepository::criteriaToFilter( array $criteria, boolean $caseSensitive ): string
```



* This method is **static**.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria |
| $caseSensitive | **boolean** | Is the search supposed to be case sensitive. Defaults to false. |


**Return Value:**

Returns string to be put on the OData $filter



---


### fqnModel

Return the fully qualified model name.

```php
HostRepository::fqnModel(  ): string
```






**Return Value:**

Fully qualified name



---


### make

Make a new model

```php
HostRepositoryInterface::make( array $attributes ): \Pisa\GizmoAPI\Repositories\Model
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** | Attributes for the model to be made |


**Return Value:**

Returns model.



---

## NewsRepository





* Full name: Pisa\GizmoAPI\Repositories\NewsRepository
* This class implements: Pisa\GizmoAPI\Repositories\NewsRepositoryInterface





### all

Get all model instances from repository

```php
NewsRepositoryInterface::all( integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances.



---


### findBy

Finds model instances by parameters

```php
NewsRepositoryInterface::findBy( array $criteria, boolean $caseSensitive, integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for. |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false. |
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances. Throws Exception on error.



---


### findOneBy

Find one model entry by parameters

```php
NewsRepositoryInterface::findOneBy( array $criteria, boolean $caseSensitive ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false |


**Return Value:**

Returns the first model entry found on current criteria. Returns null if none is found. Throws Exception on error.



---


### get

Get model by id

```php
NewsRepositoryInterface::get( integer $id ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |


**Return Value:**

Returns model. If no model is found, returns null. Throws Exception on error.



---


### has

Check if model entry exists.

```php
NewsRepositoryInterface::has( integer $id ): boolean
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |




---


### make

Make a new model

```php
NewsRepositoryInterface::make( array $attributes ): \Pisa\GizmoAPI\Repositories\Model
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** | Attributes for the model to be made |


**Return Value:**

Returns model.



---

## SessionsRepository





* Full name: Pisa\GizmoAPI\Repositories\SessionsRepository
* This class implements: Pisa\GizmoAPI\Repositories\SessionsRepositoryInterface





### all

Get all model instances from repository

```php
SessionsRepositoryInterface::all( integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances.



---


### findBy

Finds model instances by parameters

```php
SessionsRepositoryInterface::findBy( array $criteria, boolean $caseSensitive, integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for. |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false. |
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances. Throws Exception on error.



---


### findOneBy

Find one model entry by parameters

```php
SessionsRepositoryInterface::findOneBy( array $criteria, boolean $caseSensitive ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false |


**Return Value:**

Returns the first model entry found on current criteria. Returns null if none is found. Throws Exception on error.



---


### get

Get model by id

```php
SessionsRepositoryInterface::get( integer $id ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |


**Return Value:**

Returns model. If no model is found, returns null. Throws Exception on error.



---


### has

Check if model entry exists.

```php
SessionsRepositoryInterface::has( integer $id ): boolean
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |




---


### make

Make a new model

```php
SessionsRepositoryInterface::make( array $attributes ): \Pisa\GizmoAPI\Repositories\Model
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** | Attributes for the model to be made |


**Return Value:**

Returns model.



---

## UserRepository





* Full name: Pisa\GizmoAPI\Repositories\UserRepository
* Parent class: Pisa\GizmoAPI\Repositories\BaseRepository
* This class implements: Pisa\GizmoAPI\Repositories\UserRepositoryInterface





### all

Get all model instances from repository

```php
UserRepositoryInterface::all( integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances.



---


### findBy

Finds model instances by parameters

```php
UserRepositoryInterface::findBy( array $criteria, boolean $caseSensitive, integer $limit, integer $skip, string $orderBy ): array
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for. |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false. |
| $limit | **integer** | Limit the number of fetched instances. Defaults to 30. |
| $skip | **integer** | Skip number of instances (i.e. fetch the next page). Defaults to 0. |
| $orderBy | **string** | Column to order the results with. |


**Return Value:**

Returns array of model instances. Throws Exception on error.



---


### findOneBy

Find one model entry by parameters

```php
UserRepositoryInterface::findOneBy( array $criteria, boolean $caseSensitive ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria to search for |
| $caseSensitive | **boolean** | Search for case sensitive parameters. Defaults to false |


**Return Value:**

Returns the first model entry found on current criteria. Returns null if none is found. Throws Exception on error.



---


### get

Get model by id

```php
UserRepositoryInterface::get( integer $id ): \Pisa\GizmoAPI\Repositories\Model|null
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |


**Return Value:**

Returns model. If no model is found, returns null. Throws Exception on error.



---


### has

Check if model entry exists.

```php
UserRepositoryInterface::has( integer $id ): boolean
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $id | **integer** | Id of the model entry. |




---


### hasLoginName

Check if user LoginName exists.

```php
UserRepository::hasLoginName( string $loginName )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $loginName | **string** |  |




---


### hasUserEmail

Check if user email exists.

```php
UserRepository::hasUserEmail( string $userEmail )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $userEmail | **string** |  |




---


### hasUserName

Check if user username exists.

```php
UserRepository::hasUserName( string $userName )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $userName | **string** |  |




---


### __construct



```php
UserRepository::__construct( \Pisa\GizmoAPI\Contracts\Container $ioc, \Pisa\GizmoAPI\Contracts\HttpClient $client )
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $ioc | **\Pisa\GizmoAPI\Contracts\Container** |  |
| $client | **\Pisa\GizmoAPI\Contracts\HttpClient** |  |




---


### criteriaToFilter

Turn array of criteria into an OData filter

```php
UserRepository::criteriaToFilter( array $criteria, boolean $caseSensitive ): string
```



* This method is **static**.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $criteria | **array** | Array of criteria |
| $caseSensitive | **boolean** | Is the search supposed to be case sensitive. Defaults to false. |


**Return Value:**

Returns string to be put on the OData $filter



---


### fqnModel

Return the fully qualified model name.

```php
UserRepository::fqnModel(  ): string
```






**Return Value:**

Fully qualified name



---


### make

Make a new model

```php
UserRepositoryInterface::make( array $attributes ): \Pisa\GizmoAPI\Repositories\Model
```





**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| $attributes | **array** | Attributes for the model to be made |


**Return Value:**

Returns model.



---


--------
> This document was automatically generated from source code comments on 2015-11-27 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-md](https://github.com/cvuorinen/phpdoc-md)
