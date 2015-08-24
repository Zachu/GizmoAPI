<?php namespace Pisa\Api\Gizmo\Models;

interface HostInterface extends BaseModelInterface
{
    public function getProcesses();
    public function getProcess($processId);
    public function getProcessesByName($processName);
    public function createProcess($startInfo);
    public function terminateProcess($killInfo);
    public function getLastUserLoginTime();
    public function getLastUserLogoutTime();
    public function userLogout();
    public function UINotify($message, $parameters = []);
    public function setLockState($isLocked);
    public function setSecurityState($isEnabled);
    public function setOrderState($isInOrder);
    public function isFree();
}
