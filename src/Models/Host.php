<?php namespace Pisa\GizmoAPI\Models;

use Exception;
use Pisa\GizmoAPI\Repositories\BaseRepository;

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
        'IsOutOfOrder',
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

    /**
     * @see $this->defaultParameters for parameters to modify
     * @return boolean If ShowDialog is set to true, returns true if user clicks ok, false if user clicks cancel.
     * @return boolean If ShowDialog is set to false, returns true when message is sent.
     * @throws  Exception on error
     */
    public function UINotify($message, $parameters = [])
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $response = $this->client->post('Host/UINotify', array_merge($this->defaultNotifyParameters, $parameters, [
                    'hostId'  => $this->getPrimaryKeyValue(),
                    'message' => (string) $message,
                ]));
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertInteger();
                $response->assertStatusCodes(200);
                return $response->getBody();

                /**
                 *  @todo Fiddle with the responses
                 *  Old code looks like this:
                 *  if (is_int($response)) {
                 *    return true;
                 *  } else {
                 *    return false;
                 *  }
                 */
            }
        } catch (Exception $e) {
            throw new Exception("Unable to write UI notify: " . $e->getMessage());
        }
    }

    /**
     * Example:
     * <code>
     * $this->createProcess(['FileName' => 'C:\Start.bat']);
     * </code>
     *
     * @return int       Returns the process id.
     * @throws Exception on error
     */
    public function createProcess($startInfo)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_array($startInfo)) {
                throw new Exception("Start info has to be an array. Try giving a FileName parameter for example");
            } else {
                $response = $this->client->post('Host/CreateProcess', array_merge(
                    $startInfo,
                    ['hostId' => $this->getPrimaryKeyValue()]
                ));
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertInteger();
                $response->assertStatusCodes(200);

                return $response->getBody();
            }
        } catch (Exception $e) {
            throw new Exception("Unable to create a process: " . $e->getMessage());
        }
    }

    /**
     * This method cannot be used. Host is deleted via the server service
     * @return void
     * @throws Exception
     */
    public function delete()
    {
        throw new Exception("Host cannot be deleted via API. Host is deleted by via the server service");
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
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $response = $this->client->get('Host/GetLastUserLogin', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertTime();
                $response->assertStatusCodes(200);

                return strtotime($response->getBody());
            }
        } catch (Exception $e) {
            throw new Exception("Unable to last login time: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     * @note: Renamed function to getLastUserLogoutTime instead of GizmoAPI's GetLastUserLogout to better reflect what the method does.
     */
    public function getLastUserLogoutTime()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $response = $this->client->get('Host/GetLastUserLogout', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertTime();
                $response->assertStatusCodes(200);

                return strtotime($response->getBody());
            }
        } catch (Exception $e) {
            throw new Exception("Unable to last logout time: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function getProcess($processId)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_int($processId)) {
                throw new Exception("Process id has to be integer");
            } else {
                $response = $this->client->get('Host/GetProcess', [
                    'hostId'    => $this->getPrimaryKeyValue(),
                    'processId' => (int) $processId,
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertArray();
                $response->assertStatusCodes(200);

                return $response->getBody();
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get processes by id: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function getProcesses(array $criteria = [], $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];

        if (!empty($criteria)) {
            $options['$filter'] = BaseRepository::criteriaToFilter($criteria, $caseSensitive);
        }
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $response = $this->client->get('Host/GetProcesses', array_merge(
                    $options,
                    ['hostId' => $this->getPrimaryKeyValue()]
                ));
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertArray();
                $response->assertStatusCodes(200);

                return $response->getBody();
            }
        } catch (Exception $e) {
            throw new Exception("Unable to list processes: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     */
    public function getProcessesByName($processName)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_string($processName)) {
                throw new Exception("Process name has to be string");
            } else {
                $response = $this->client->get('Host/GetProcesses', [
                    'hostId'      => $this->getPrimaryKeyValue(),
                    'processName' => (string) $processName,
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertArray();
                $response->assertStatusCodes(200);

                return $response->getBody();
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get processes by name: " . $e->getMessage());
        }
    }

    /**
     * @throws  Exception on error
     * @note This should probably be in SessionModel or SessionsRepository based on how Gizmo API is built, but I think this is good addition here also
     */
    public function isFree()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $response = $this->client->get('Sessions/GetActive');
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertArray();
                $response->assertStatusCodes(200);

                foreach ($response->getBody() as $row) {
                    if (isset($row['HostId']) && (int) $row['HostId'] === (int) $this->getPrimaryKeyValue()) {
                        return false;
                    }
                }

                return true;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get free status: " . $e->getMessage());
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
     * @throws  Exception on error
     */
    public function setLockState($isLocked)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_bool($isLocked)) {
                throw new Exception("Provided lock state isn't boolean");
            } else {
                $response = $this->client->post('Host/SetLockState', [
                    'hostId' => $this->getPrimaryKeyValue(),
                    'locked' => ($isLocked ? 'true' : 'false'),
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertEmpty();
                $response->assertStatusCodes(204);

                $this->IsLocked = $isLocked;
                return true;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set lock state: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function setOrderState($isOutOfOrder)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_bool($isOutOfOrder)) {
                throw new Exception("Provided order state isn't boolean");
            } else {
                $response = $this->client->post('Host/SetOrderState', [
                    'hostId'  => $this->getPrimaryKeyValue(),
                    'inOrder' => (!$isOutOfOrder ? 'true' : 'false'),
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertEmpty();
                $response->assertStatusCodes(204);

                $this->IsOutOfOrder = $isOutOfOrder;
                return true;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set order state: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function setSecurityState($isEnabled)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_bool($isEnabled)) {
                throw new Exception("Provided security state isn't boolean");
            } else {
                $response = $this->client->post('Host/SetSecurityState', [
                    'hostId'  => $this->getPrimaryKeyValue(),
                    'enabled' => ($isEnabled ? 'true' : 'false'),
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertEmpty();
                $response->assertStatusCodes(204);

                $this->IsSecurityEnabled = $isEnabled;
                return true;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set security state: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function terminateProcess($killInfo)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_array($killInfo)) {
                throw new Exception("Kill info has to be an array. Try giving a FileName parameter for example");
            } else {
                $response = $this->client->post('Host/TerminateProcess', array_merge(
                    $killInfo,
                    ['hostId' => $this->getPrimaryKeyValue()]
                ));
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertEmpty();
                $response->assertStatusCodes(204);
            }
        } catch (Exception $e) {
            throw new Exception("Unable to terminate processes: " . $e->getMessage());
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
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $response = $this->client->post('Host/UserLogout', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ]);
                if ($response === null) {
                    throw new Exception("Response failed");
                }

                $response->assertEmpty();
                $response->assertStatusCodes(204);

                return true;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to log user out: " . $e->getMessage());
        }
    }

    /**
     * This method cannot be used. Host is created by connecting new host client to the server service
     * @internal Use $this->save() for really create a host.
     * @throws Exception
     */
    protected function create()
    {
        throw new Exception("New host cannot be created via API. Host is created by connecting new host client to the server service");
    }

    /**
     * Update the host instance.
     * @internal Use $this->save() for really update a host.
     * @return Host Return $this for chaining.
     */
    protected function update()
    {
        try {
            foreach ($this->changed() as $key => $newValue) {
                if ($key == 'IsOutOfOrder') {
                    $this->setOrderState($newValue);
                } elseif ($key == 'IsSecurityEnabled') {
                    $this->setSecurityState($newValue);
                } elseif ($key == 'IsLocked') {
                    $this->setLockState($newValue);
                } else {
                    throw new Exception("Host attributes are only changeable from server service.");
                }
            }

            return $this;
        } catch (Exception $e) {
            throw new Exception("Unable to update host: " . $e->getMessage());
        }
    }
}
