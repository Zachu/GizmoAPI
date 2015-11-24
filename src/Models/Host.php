<?php namespace Pisa\Api\Gizmo\Models;

use Exception;

class Host extends BaseModel implements HostInterface
{
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

    protected $fillable = [
        'IsSecurityEnabled',
        'IsOutOfOrder',
        'IsLocked',
    ];

    protected $defaultNotifyParameters = [
        'ShowDialog'     => 'false', //Otherwise the HTTP Request waits for user input
        'StarupLocation' => 'Manual',
        'Left'           => 0,
        'Top'            => 0,
        'Icon'           => 'Information',
    ];

    protected $client;

    public function getDefaultNotifyParameters()
    {
        return $this->defaultNotifyParameters;
    }

    /**
     * This method cannot be used. Host is created by connecting new host client to the server service
     * @throws Exception
     */
    protected function create()
    {
        throw new Exception("New host cannot be created via API. Host is created by connecting new host client to the server service");
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
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

    /**
     * This method cannot be used. Host is deleted via the server service
     * @throws Exception
     */
    public function delete()
    {
        throw new Exception("Host cannot be deleted via API. Host is deleted by via the server service");
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function getProcesses()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $result = $this->client->get('Host/GetProcesses', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ])->getBody();

                if (is_array($result)) {
                    return $result;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }

            }
        } catch (Exception $e) {
            throw new Exception("Unable to list processes: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
                $result = $this->client->get('Host/GetProcess', [
                    'hostId'    => $this->getPrimaryKeyValue(),
                    'processId' => (int) $processId,
                ])->getBody();

                if (is_array($result)) {
                    return $result;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get processes by id: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
                $result = $this->client->get('Host/GetProcesses', [
                    'hostId'      => $this->getPrimaryKeyValue(),
                    'processName' => (string) $processName,
                ])->getBody();

                if (is_array($result)) {
                    return $result;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get processes by name: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * Example:
     * <code>
     * $this->createProcess(['FileName' => 'C:\Start.bat']);
     * </code>
     *
     * {@inheritDoc}
     * @throws  Exception on error
     */
    public function createProcess($startInfo)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_array($startInfo)) {
                throw new Exception("Start info has to be an array. Try giving a FileName parameter for example");
            } else {
                $result = $this->client->post('Host/CreateProcess', array_merge(
                    $startInfo,
                    ['hostId' => $this->getPrimaryKeyValue()]
                ));

                if (is_object($result) && is_integer($result->getBody())) {
                    return $result->getBody();
                } elseif (is_object($result) && $result->getStatusCode() === 500) {
                    return false;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }

                //@todo check return values
            }
        } catch (Exception $e) {
            throw new Exception("Unable to create a process: " . $e->getMessage());
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
                $result = $this->client->post('Host/TerminateProcess', array_merge(
                    $killInfo,
                    ['hostId' => $this->getPrimaryKeyValue()]
                ));

                if ($result->getStatusCode() === 204) {
                    return true;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
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
     * @note Renamed function to getLastUserLoginTime instead of GizmoAPI's GetLastUserLogin to better reflect what the method does.
     */
    public function getLastUserLoginTime()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $result = $this->client->get('Host/GetLastUserLogin', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ])->getBody();

                if (!is_string($result) || strtotime($result) === false) {
                    throw new Exception("Cannot parse the result ($result)");
                }

                return strtotime($result);
            }
        } catch (Exception $e) {
            throw new Exception("Unable to last login time: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * @throws  Exception on error
     * @note: Renamed function to getLastUserLogoutTime instead of GizmoAPI's GetLastUserLogout to better reflect what the method does.
     */
    public function getLastUserLogoutTime()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $result = $this->client->get('Host/GetLastUserLogout', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ])->getBody();

                if (!is_string($result) || strtotime($result) === false) {
                    throw new Exception("Cannot parse the result ($result)");
                }

                return strtotime($result);
            }
        } catch (Exception $e) {
            throw new Exception("Unable to last logout time: " . $e->getMessage());
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
                $result = $this->client->post('Host/UserLogout', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ]);

                if ($result->getStatusCode() === 204) {
                    return true;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to log user out: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
                $result = $this->client->post('Host/UINotify', array_merge($this->defaultNotifyParameters, $parameters, [
                    'hostId'  => $this->getPrimaryKeyValue(),
                    'message' => (string) $message,
                ]));

                /**
                 *  @todo Fiddle with the responses
                 *  Old code looks like this:
                 *  if (is_int($result)) {
                 *    return true;
                 *  } else {
                 *    return false;
                 *  }
                 */
                if ($result->getStatusCode() === 204) {
                    return true;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to write UI notify: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
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
                $result = $this->client->post('Host/SetLockState', [
                    'hostId' => $this->getPrimaryKeyValue(),
                    'locked' => ($isLocked ? 'true' : 'false'),
                ]);

                if ($result->getStatusCode() === 204) {
                    $this->IsLocked = $isLocked;
                    return true;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
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
    public function setSecurityState($isEnabled)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_bool($isEnabled)) {
                throw new Exception("Provided security state isn't boolean");
            } else {
                $result = $this->client->post('Host/SetSecurityState', [
                    'hostId'  => $this->getPrimaryKeyValue(),
                    'enabled' => ($isEnabled ? 'true' : 'false'),
                ]);

                if ($result->getStatusCode() === 204) {
                    $this->IsSecurityEnabled = $isEnabled;
                    return true;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
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
    public function setOrderState($isOutOfOrder)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_bool($isOutOfOrder)) {
                throw new Exception("Provided order state isn't boolean");
            } else {
                $result = $this->client->post('Host/SetOrderState', [
                    'hostId'  => $this->getPrimaryKeyValue(),
                    'inOrder' => (!$isOutOfOrder ? 'true' : 'false'),
                ]);

                if (is_object($result) && $result->getStatusCode() === 204) {
                    $this->IsOutOfOrder = $isOutOfOrder;
                    return true;
                } else {
                    throw new Exception("Unexpected response: " . (is_object($result) ? $result->getStatusCode() . " " . $result->getReasonPhrase() : gettype($result) . ":" . $result));
                }
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
     * @note This should probably be in SessionModel or SessionsRepository based on how Gizmo API is built, but I think this is good addition here also
     */
    public function isFree()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $result = $this->client->get('Sessions/GetActive')->getBody();

                if (!is_array($result)) {
                    throw new Exception("Unexpected response for isFree. Expected Array, got " . gettype($result));
                } else {
                    foreach ($result as $row) {
                        if (isset($row['HostId']) && (int) $row['HostId'] === (int) $this->getPrimaryKeyValue()) {
                            return false;
                        }
                    }

                    return true;
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get free status: " . $e->getMessage());
        }
    }
}
