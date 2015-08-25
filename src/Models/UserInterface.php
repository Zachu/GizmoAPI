<?php namespace Pisa\Api\Gizmo\Models;

use Pisa\Api\Gizmo\Repositories\UserRepositoryInterface;

interface UserInterface extends BaseModelInterface
{
    public function getLoggedInHostId();
    public function isLoggedIn();
    public function lastLoginTime();
    public function lastLogoutTime();
    public function login(HostInterface $host);
    public function logout();
    public function rename(UserRepositoryInterface $repository, $newUserName);
    public function setEmail(UserRepositoryInterface $repository, $newEmail);
    public function setPassword($newPassword);
    public function setUserGroup($groupId);
}
