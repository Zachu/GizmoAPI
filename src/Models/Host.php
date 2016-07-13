<?php namespace Pisa\GizmoAPI\Models;

use Pisa\GizmoAPI\Repositories\BaseRepository;
use Pisa\GizmoAPI\Exceptions\InternalException;
use Pisa\GizmoAPI\Exceptions\RequirementException;
use Pisa\GizmoAPI\Exceptions\NotImplementedException;
use Pisa\GizmoAPI\Exceptions\InvalidArgumentException;

class Host extends BaseModel implements HostInterface
{
    /** @var array Default parameters for UINotify */
    protected $defaultNotifyParameters = [
        /** @var string Dialog title */
        'Title'          => '',

        /** @var int Dialog x-position */
        'Left'           => 0,

        /** @var int Dialog y-position */
        'Top'            => 0,

        /** @var bool Does the request wait for user input */
        'ShowDialog'     => 'false',

        /** @var bool Start the dialog on top of everything */
        'TopMost'        => 'true',

        /** @var int Dialog width in px */
        'Width'          => 0,

        /** @var int Dialog height in px */
        'Height'         => 0,

        /** @var int ProcessID of the owner process  */
        'Owner'          => '',

        /** @var bool Allow dragging the dialog */
        'AllowDrag'      => 'true',

        /** @var bool Remove dialog buttons */
        'NoButtons'      => 'false',

        /** @var bool Allow closing of the dialog */
        'AllowClosing'   => 'true',

        /**
         * @var string Startup location of the dialog
         *
         * Note that this really is StarupLocation, not StartupLocation
         *
         * Possible values: Manual, CenterOwner, CenterScreen
         * @see https://msdn.microsoft.com/en-us/library/system.windows.windowstartuplocation(v=vs.110).aspx
         */
        'StarupLocation' => 'Manual',

        /**
         * @var string How to stretch the dialog size
         *
         * Possible values: Height, Manual, Width, WidthAndHeight
         * @see https://msdn.microsoft.com/en-us/library/system.windows.sizetocontent(v=vs.110).aspx
         */
        'SizeToContent'  => 'WidthAndHeight',

        /**
         * @var string Icon to be showed in the window
         *
         * Possible values: Asterisk, Error, Exclamation, Hand, Information, None, Question, Stop, Warning
         * @see https://msdn.microsoft.com/en-us/library/system.windows.messageboximage(v=vs.110).aspx
         */
        'Icon'           => 'Information',

        /**
         * @var string Which buttons should be added
         *
         * Possible values: OK, OKCancel, YesNo, YesNoCancel
         * @see https://msdn.microsoft.com/en-us/library/system.windows.messageboxbutton(v=vs.110).aspx
         */
        'Buttons'        => 'OK',

        /** @var bool Show dialog activated */
        'ShowActivated'  => 'false',

        /** @var int Max width of the dialog */
        'MaxWidth'       => '',

        /** @var int Max height of the dialog */
        'MaxHeight'      => '',
    ];

    /** @{inheritDoc} */
    protected $fillable = [
        'IsSecurityEnabled',
        'IsInOrder',
        'IsLocked',
    ];

    /** @{inheritDoc} */
    protected $guarded = [
        'Id',
        'OsInfo',
        'State',
        'IsMaintenanceMode',
        'Module',
        'HostName',
        'IpAddress',
        'Port',
        'MaxAddress',
        'Registered',
        'Number',
        'HasValidDispatcher',
        'GroupId',
    ];

    public function __toString()
    {
        if ($this->HostName) {
            return 'Host[' . $this->HostName . ']';
        } else {
            return parent::__toString();
        }
    }

    /**
     * Create a new process
     *
     * Example:
     * <code>
     * $this->createProcess(['FileName' => 'C:\Start.bat']);
     * f</code>
     *
     * @return int       Returns the process id.
     * @throws \Exception on error
     */
    public function createProcess($startInfo)
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } elseif (!is_array($startInfo)) {
            throw new InvalidArgumentException("Start info has to be an array. Try giving a FileName parameter for example");
        } else {
            $this->logger->notice("[HOST $this] Starting process: "
                . json_encode($startInfo));

            $response = $this->client->post('Host/CreateProcess', array_merge(
                $startInfo,
                ['hostId' => $this->getPrimaryKeyValue()]
            ));
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertInteger();
            $response->assertStatusCodes(200);

            return $response->getBody();
        }
    }

    /**
     * This method cannot be used. Host is deleted via the server service
     * @return void
     * @throws \Exception
     */
    public function delete()
    {
        throw new NotImplementedException("Host cannot be deleted via API. "
            . "Host is deleted by the server service");
    }

    /** @internal Basically for testing */
    public function getDefaultNotifyParameters()
    {
        return $this->defaultNotifyParameters;
    }

    /**
     * @throws  Exception on error
     * @note Renamed function to getLastUserLoginTime instead of GizmoAPI's GetLastUserLogin to better reflect what the method does.
     */
    public function getLastUserLoginTime()
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } else {
            $response = $this->client->get('Host/GetLastUserLogin', [
                'hostId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertTime();
            $response->assertStatusCodes(200);

            return strtotime($response->getBody());
        }
    }

    /**
     * @throws  Exception on error
     * @note    Renamed function to getLastUserLogoutTime instead of GizmoAPI's GetLastUserLogout to better reflect what the method does.
     */
    public function getLastUserLogoutTime()
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } else {
            $response = $this->client->get('Host/GetLastUserLogout', [
                'hostId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertTime();
            $response->assertStatusCodes(200);

            return strtotime($response->getBody());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function getProcess($processId)
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } elseif (!is_int($processId)) {
            throw new InvalidArgumentException("Process id has to be integer");
        } else {
            $response = $this->client->get('Host/GetProcess', [
                'hostId'    => $this->getPrimaryKeyValue(),
                'processId' => (int) $processId,
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        }
    }

    /**
     * @throws  Exception on error
     */
    public function getProcesses(array $criteria = [], $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        // Gather filtering info to options
        $options = ['$skip' => $skip, '$top' => $limit];
        if (!empty($criteria)) {
            $options['$filter'] = BaseRepository::criteriaToFilter($criteria, $caseSensitive);
        }
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } else {
            $response = $this->client->get('Host/GetProcesses', array_merge(
                $options,
                ['hostId' => $this->getPrimaryKeyValue()]
            ));
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        }
    }

    /**
     * @throws  Exception on error
     */
    public function getProcessesByName($processName)
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } elseif (!is_string($processName)) {
            throw new InvalidArgumentException("Process name has to be string");
        } else {
            $response = $this->client->get('Host/GetProcesses', [
                'hostId'      => $this->getPrimaryKeyValue(),
                'processName' => (string) $processName,
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        }
    }

    /**
     * @throws  Exception on error
     * @note This should probably be in SessionModel or SessionsRepository based on how Gizmo API is built, but I think this is good addition here also
     */
    public function isFree()
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } else {
            $response = $this->client->get('Sessions/GetActive');
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            // Find if this host is in the active session table
            foreach ($response->getBody() as $row) {
                if (isset($row['HostId']) && (int) $row['HostId'] === (int) $this->getPrimaryKeyValue()) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * Shorthand for HasValidDispatcher attribute
     * @return boolean
     */
    public function isTurnedOn()
    {
        return $this->HasValidDispatcher;
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setLockState($isLocked)
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } elseif (!is_bool($isLocked)) {
            throw new InvalidArgumentException("Provided lock state isn't boolean");
        } else {
            $this->logger->info("[HOST $this] Setting lock state "
                . ($isLocked ? 'on' : 'off'));

            $response = $this->client->post('Host/SetLockState', [
                'hostId' => $this->getPrimaryKeyValue(),
                'locked' => ($isLocked ? 'true' : 'false'),
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            $this->IsLocked = $isLocked;
        }
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setOrderState($isInOrder)
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } elseif (!is_bool($isInOrder)) {
            throw new InvalidArgumentException("Provided order state isn't boolean");
        } else {
            $this->logger->info("[HOST $this] Setting order state "
                . ($isInOrder ? 'in order' : 'out of order'));

            $response = $this->client->post('Host/SetOrderState', [
                'hostId'  => $this->getPrimaryKeyValue(),
                'inOrder' => ($isInOrder ? 'true' : 'false'),
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            $this->IsInOrder = $isInOrder;
        }
    }

    /**
     * @return  void
     * @throws  Exception on error
     */
    public function setSecurityState($isEnabled)
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } elseif (!is_bool($isEnabled)) {
            throw new InvalidArgumentException("Provided security state isn't boolean");
        } else {
            $this->logger->info("[HOST $this] Setting security state "
                . ($isEnabled ? 'on' : 'off'));

            $response = $this->client->post('Host/SetSecurityState', [
                'hostId'  => $this->getPrimaryKeyValue(),
                'enabled' => ($isEnabled ? 'true' : 'false'),
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            $this->IsSecurityEnabled = $isEnabled;
        }
    }

    /**
     * @throws  Exception on error
     */
    public function terminateProcess($killInfo)
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } elseif (!is_array($killInfo)) {
            throw new InvalidArgumentException('Kill info has to be an array. '
                . 'Try giving a FileName parameter for example');
        } else {
            $this->logger->notice("[HOST $this] Terminating processes: "
                . json_encode($killInfo));

            $response = $this->client->post('Host/TerminateProcess', array_merge(
                $killInfo,
                ['hostId' => $this->getPrimaryKeyValue()]
            ));
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);
        }
    }

    /**
     * @see $this->defaultParameters for parameters to modify
     * @return boolean If ShowDialog is set to true, returns true if user clicks ok, false if user clicks cancel.
     * @return boolean If ShowDialog is set to false, returns true when message is sent.
     * @throws  Exception on error
     */
    public function uiNotify($message, $parameters = [])
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } else {
            $this->logger->info("[HOST $this] Sending UINotify: $message");

            $response = $this->client->post('Host/UINotify', array_merge($this->defaultNotifyParameters, $parameters, [
                'hostId'  => $this->getPrimaryKeyValue(),
                'message' => (string) $message,
            ]));
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertInteger();
            $response->assertStatusCodes(200);
            return $response->getBody();
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function userLogout()
    {
        if ($this->exists() === false) {
            throw new RequirementException('Model does not exist');
        } else {
            $this->logger->notice("[HOST $this] Logging user out");

            $response = $this->client->post('Host/UserLogout', [
                'hostId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new InternalException('Response failed');
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            return true;
        }
    }

    /**
     * This method cannot be used. Host is created by connecting new host client to the server service
     * @internal Use $this->save() for really create a host.
     * @throws \Exception
     */
    protected function create()
    {
        throw new NotImplementedException('New host cannot be created via API. '
            . 'Host is created by connecting new host client to the server service');
    }

    /**
     * Update the host instance.
     * @internal Use $this->save() for really update a host.
     * @return Host Return $this for chaining.
     */
    protected function update()
    {
        foreach ($this->changed() as $key => $newValue) {
            if ($key == 'IsInOrder') {
                $this->setOrderState($newValue);
            } elseif ($key == 'IsSecurityEnabled') {
                $this->setSecurityState($newValue);
            } elseif ($key == 'IsLocked') {
                $this->setLockState($newValue);
            } else {
                throw new NotImplementedException("Host attributes are only " .
                    "changeable from server service.");
            }
        }

        return $this;
    }
}
