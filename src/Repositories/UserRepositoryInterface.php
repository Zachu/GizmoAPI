<?php namespace Pisa\Api\Gizmo\Repositories;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function hasLoginName($loginName);
    public function hasUserEmail($userEmail);
    public function hasUserName($userName);
}
