<?php namespace Pisa\Api\Gizmo\Repositories;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Check if user LoginName exists.
     * @param  string $loginName LoginName of the user
     * @return boolean
     * @api
     */
    public function hasLoginName($loginName);

    /**
     * Check if user email exists.
     * @param  string $userEmail Email of the user
     * @return boolean
     * @api
     */
    public function hasUserEmail($userEmail);

    /**
     * Check if user username exists.
     * @param  string $userName UserName of the user
     * @return boolean
     * @api
     */
    public function hasUserName($userName);
}
