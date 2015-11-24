<?php namespace Pisa\Api\Gizmo\Models;

use Pisa\Api\Gizmo\Repositories\UserRepositoryInterface;

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
     * @param  HostInterface $host Host to log in
     * @return true                Returns true on success
     * @throws Exception on error
     */
    public function login(HostInterface $host);

    /**
     * Log user out from a host
     * @return true Returns true on success
     * @throws Exception on error
     */
    public function logout();

    /**
     * Renames a user
     * @param  UserRepositoryInterface $repository  User repository to check whether the new username is available
     * @param  string                  $newUserName New username
     * @return true                    Returns true on success
     * @throws Exception               on error
     */
    public function rename(UserRepositoryInterface $repository, $newUserName);

    /**
     * Change the user email
     * @param UserRepositoryInterface $repository User repository to check whether the new email is available
     * @param string                  $newEmail   New email
     * @return true                   Returns true on success
     * @throws Exception              on error
     */
    public function setEmail(UserRepositoryInterface $repository, $newEmail);

    /**
     * Set new password for the user
     * @param string $newPassword New password
     * @return true on success
     * @throws Exception on error
     */
    public function setPassword($newPassword);

    /**
     * Set user to a new user group
     * @param integer $groupId New group id
     * @return true on success
     * @throws Exception on error
     */
    public function setUserGroup($groupId);
}
