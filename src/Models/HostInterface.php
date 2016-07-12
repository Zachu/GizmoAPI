<?php namespace Pisa\GizmoAPI\Models;

interface HostInterface extends BaseModelInterface
{
    /**
     * Create a new process
     * @api
     * @param  array $startInfo  Information about starting the process
     */
    public function createProcess($startInfo);

    /**
     * Get the time of last user login
     * @api
     * @return int Unix timestamp
     */
    public function getLastUserLoginTime();

    /**
     * Get the time of last user logout
     * @api
     * @return int Unix timestamp
     */
    public function getLastUserLogoutTime();

    /**
     * Get a single process by its id
     * @api
     * @param  integer $processId
     * @return array Array representation of the process
     */
    public function getProcess($processId);

    /**
     * Get all processes running on the host
     * @param  array   $criteria      Array of criteria to limit processes by. Defaults to array()
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false.
     * @param  integer $limit         Limit the number of fetched instances. Defaults to 30.
     * @param  integer $skip          Skip number of instances (i.e. fetch the next page). Defaults to 0.
     * @param  string  $orderBy       Column to order the results with.
     * @return array processess
     * @api
     */
    public function getProcesses(array $criteria = [], $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null);

    /**
     * Get all processes running on the host filtered by process name
     * @api
     * @param  string $processName
     * @return array processes
     */
    public function getProcessesByName($processName);

    /**
     * Checks if the host is free
     * @api
     * @return boolean
     */
    public function isFree();

    /**
     * Set the host to locked state
     * @api
     * @param boolean $isLocked true to lock, false to unlock
     */
    public function setLockState($isLocked);

    /**
     * Set the host order state
     * @api
     * @param boolean $isInOrder true to set the host in order, false to set out of order
     */
    public function setOrderState($isInOrder);

    /**
     * Set the host security state
     * @api
     * @param boolean $isEnabled true to enable security profiles, false to disable
     */
    public function setSecurityState($isEnabled);

    /**
     * Terminate processes
     * @api
     * @param  array $killInfo Information about killing the processes
     * @return true on success
     */
    public function terminateProcess($killInfo);

    /**
     * Send a message dialog to host
     * @api
     * @param string $message    Message to be sent
     * @param array  $parameters Message parameters
     * @return true on success, false on failure
     */
    public function uiNotify($message, $parameters = []);

    /**
     * Logs user out from the host
     * @api
     * @return true on success
     */
    public function userLogout();
}
