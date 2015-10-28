<?php namespace Pisa\Api\Gizmo\Repositories;

interface HostRepositoryInterface extends BaseRepositoryInterface
{
    public function getByNumber($hostNumber);
}
