<?php namespace Pisa\GizmoAPI\Models;

use Pisa\GizmoAPI\Repositories\UserRepositoryInterface;

interface UserInterface extends BaseModelInterface
{
    /**
     * Get the Host id where the user is logged in
     * @return integer|false Returns integer if user is logged on a host. Otherwise returns false
     */
    public function getLoggedInHostId();

    /**
     * Check if user is logged in
     * @return boolean
     */
    public function isLoggedIn();

    /**
     * Get the time of last login to a host
     * @return integer Unix timestamp
     */
    public function lastLoginTime();

    /**
     * Get the time of last logout from a host
     * @return integer Unix timestamp
     */
    public function lastLogoutTime();

    /**
     * Log user in to a host
     * @param  Pisa\GizmoAPI\Models\HostInterface $host Host to log in
     */
    public function login(HostInterface $host);

    /**
     * Log user out from a host
     */
    public function logout();

    /**
     * Renames a user
     * @param  Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository User repository to check whether the new username is available
     * @param  string                                             $newUserName New username
     */
    public function rename(UserRepositoryInterface $repository, $newUserName);

    /**
     * Resets the user password so that it will be prompted on next login
     * @return void
     */
    public function resetPassword();

    /**
     * Change the user email
     * @param  Pisa\GizmoAPI\Repositories\UserRepositoryInterface $repository User repository to check whether the new email is available
     * @param  string                                             $newEmail   New email
     */
    public function setEmail(UserRepositoryInterface $repository, $newEmail);

    /**
     * Set new password for the user
     * @param  string $newPassword New password
     */
    public function setPassword($newPassword);

    /**
     * Set user to a new user group
     * @param  integer $groupId New group id
     * @return true on success
     */
    public function setUserGroup($groupId);
}
