<?php namespace Pisa\Api\Gizmo\Models;

interface HostInterface extends BaseModelInterface
{
    /**
     * Get all processes running on the host
     * @return array processess
     */
    public function getProcesses();

    /**
     * Get a single process by its id
     * @param  integer $processId
     * @return array Array representation of the process
     */
    public function getProcess($processId);

    /**
     * Get all processes running on the host filtered by process name
     * @param  string $processName
     * @return array processes
     */
    public function getProcessesByName($processName);

    /**
     * Create a new process
     * @param  array $startInfo  Information about starting the process
     * @return int|false Returns the process id on success. False if process couldn't be started.
     */
    public function createProcess($startInfo);

    /**
     * Terminate processes
     * @param  array $killInfo Information about killing the processes
     * @return true on success
     */
    public function terminateProcess($killInfo);

    /**
     * Get the time of last user login
     * @return int Unix timestamp
     */
    public function getLastUserLoginTime();

    /**
     * Get the time of last user logout
     * @return int Unix timestamp
     */
    public function getLastUserLogoutTime();

    /**
     * Logs user out from the host
     * @return true on success
     */
    public function userLogout();

    /**
     * Send a message dialog to host
     * @param string $message    Message to be sent
     * @param array  $parameters Message parameters
     * @return true on success, false on failure
     */
    public function UINotify($message, $parameters = []);

    /**
     * Set the host to locked state
     * @param boolean $isLocked true to lock, false to unlock
     */
    public function setLockState($isLocked);

    /**
     * Set the host security state
     * @param boolean $isEnabled true to enable security profiles, false to disable
     */
    public function setSecurityState($isEnabled);

    /**
     * Set the host order state
     * @param boolean $isInOrder true to set the host in order, false to set out of order
     */
    public function setOrderState($isInOrder);

    /**
     * Checks if the host is free
     * @return boolean
     */
    public function isFree();
}
