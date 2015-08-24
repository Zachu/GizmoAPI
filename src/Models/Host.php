<?php namespace Pisa\Api\Gizmo\Models;

use Exception;
use Pisa\Api\Gizmo\Adapters\HttpClientAdapter as HttpClient;

class Host extends BaseModel implements HostInterface
{
    protected $client;

    public function __construct(HttpClient $client, array $attributes = [])
    {
        $this->client = $client;
        $this->fill($attributes, true);
    }

    protected function create()
    {
        throw new Exception("New host cannot be created via API. Host is created by connecting new host client to the server service");
    }

    protected function update()
    {
        throw new Exception("Host cannot be updated via API. Host is updated via the server service");
    }

    public function delete()
    {
        throw new Exception("Host cannot be deleted via API. Host is deleted by via the server service");
    }

    public function getProcesses()
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                $result = $this->client->get('Host/GetProcesses', [
                    'hostId' => $this->getPrimaryKeyValue(),
                ])->getBody();

                return $result;
            }
        } catch (Exception $e) {
            throw new Exception("Unable to list processes: " . $e->getMessage());
        }
    }

    public function getProcess($processId)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_int($processId)) {
                throw new Exception("Process id has to be integer");
            } else {
                $result = $this->client->get('Host/GetProcess', [
                    'hostId' => $this->getPrimaryKeyValue(),
                    'processId' => (int) $processId,
                ])->getBody();

                return $result;
                //@todo check return values
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get processes by id: " . $e->getMessage());
        }
    }

    public function getProcessesByName($processName)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_string($processName)) {
                throw new Exception("Process name has to be string");
            } else {
                $result = $this->client->get('Host/GetProcesses', [
                    'hostId' => $this->getPrimaryKeyValue(),
                    'processName' => (string) $processName,
                ])->getBody();

                return $result;
                //@todo check return values
            }
        } catch (Exception $e) {
            throw new Exception("Unable to get processes by name: " . $e->getMessage());
        }
    }

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
                ))->getBody();

                return $result;
                //@todo check return values
            }
        } catch (Exception $e) {
            throw new Exception("Unable to create a process: " . $e->getMessage());
        }
    }

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
                    throw new Exception("Unexpected response for terminateProcess: " . $result->getStatusCode() . " " . $result->getReasonPhrase());
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to terminate processes: " . $e->getMessage());
        }
    }

    /**
     * @note: Renamed function to getLastUserLoginTime instead of GizmoAPI's GetLastUserLogin to better reflect
     * what the method does.
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
     * @note: Renamed function to getLastUserLogoutTime instead of GizmoAPI's GetLastUserLogout to better reflect
     * what the method does.
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
                    throw new Exception("Unexpected response for UserLogout: " . $result->getStatusCode() . " " . $result->getReasonPhrase() . ": " . $result);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to log user out: " . $e->getMessage());
        }
    }

    public function UINotify($message, $parameters = [])
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } else {
                //@todo play with $parameters. No idea how to currently work with them.
                //Parameters probably don't work currently. In fact, they don't.
                $result = $this->client->post('Host/UINotify', [
                    'hostId' => $this->getPrimaryKeyValue(),
                    'message' => $message,
                    'parameters' => implode($parameters),
                ]);

                //@todo Also no idea what's the reply about. Fiddle with this too!
                if ($result->getStatusCode() === 204) {
                    return true;
                } else {
                    throw new Exception("Unexpected response for UINotify: " . $result->getStatusCode() . " " . $result->getReasonPhrase() . ": " . $result);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to write UI notify: " . $e->getMessage());
        }
    }

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
                    return true;
                } else {
                    throw new Exception("Unexpected response for setLockState: " . $result->getStatusCode() . " " . $result->getReasonPhrase() . ": " . $result);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set lock state: " . $e->getMessage());
        }
    }

    public function setSecurityState($isEnabled)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_bool($isEnabled)) {
                throw new Exception("Provided security state isn't boolean");
            } else {
                $result = $this->client->post('Host/SetSecurityState', [
                    'hostId' => $this->getPrimaryKeyValue(),
                    'enabled' => ($isEnabled ? 'true' : 'false'),
                ]);

                if ($result->getStatusCode() === 204) {
                    return true;
                } else {
                    throw new Exception("Unexpected response for setSecurityState: " . $result->getStatusCode() . " " . $result->getReasonPhrase() . ": " . $result);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set security state: " . $e->getMessage());
        }
    }

    public function setOrderState($isInOrder)
    {
        try {
            if ($this->exists() === false) {
                throw new Exception("Model does not exist");
            } elseif (!is_bool($isInOrder)) {
                throw new Exception("Provided order state isn't boolean");
            } else {
                $result = $this->client->post('Host/SetOrderState', [
                    'hostId' => $this->getPrimaryKeyValue(),
                    'inOrder' => ($isInOrder ? 'true' : 'false'),
                ]);

                if ($result->getStatusCode() === 204) {
                    return true;
                } else {
                    throw new Exception("Unexpected response for setOrderState: " . $result->getStatusCode() . " " . $result->getReasonPhrase() . ": " . $result);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Unable to set order state: " . $e->getMessage());
        }
    }

    /**
     * This should probably be in SessionModel or SessionsRepository based on how Gizmo API is built, but I think this is good addition here also
     * @return boolean [description]
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
            throw new Exceptoin("Unable to get free status: " . $e->getMessage());
        }
    }
}
