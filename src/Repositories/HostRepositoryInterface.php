<?php namespace Pisa\GizmoAPI\Repositories;

interface HostRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Gets hosts by number
     * @param  integer $hostNumber Number of the hosts to be searched for
     * @return array               Array of hosts that match the numbner
     * @api
     */
    public function getByNumber($hostNumber);
}
