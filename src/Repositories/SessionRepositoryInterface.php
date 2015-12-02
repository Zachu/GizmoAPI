<?php namespace Pisa\GizmoAPI\Repositories;

interface SessionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Finds active sessions by criteria
     * @param  array   $criteria      Array of criteria to search for.
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false.
     * @param  integer $limit         Limit the number of fetched instances. Defaults to 30.
     * @param  integer $skip          Skip number of instances (i.e. fetch the next page). Defaults to 0.
     * @param  string  $orderBy       Column to order the results with.
     * @return array                  List of active sessions filtered by criteria
     */
    public function findActiveBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderby = null);

    /**
     * Find active sessions with additional information by criteria
     * @param  array   $criteria      Array of criteria to search for.
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false.
     * @param  integer $limit         Limit the number of fetched instances. Defaults to 30.
     * @param  integer $skip          Skip number of instances (i.e. fetch the next page). Defaults to 0.
     * @param  string  $orderBy       Column to order the results with.
     * @return array                  List of active sessions filtered by criteria
     */
    public function findActiveInfosBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderby = null);

    /**
     * Finds one active session by criteria
     * @param  array   $criteria      Array of criteria to search for.
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false.
     * @return array                  One active session filtered by criteria
     */
    public function findOneActiveBy(array $criteria, $caseSensitive = false);

    /**
     * Find one active session with additional information by criteria
     * @param  array   $criteria      Array of criteria to search for.
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false.
     * @return array                  One active session filtered by criteria
     */
    public function findOneActiveInfosBy(array $criteria, $caseSensitive = false);

    /**
     * Get all active sessions
     * @return array
     */
    public function getActive($limit = 30, $skip = 0, $orderby = null);

    /**
     * Get all active sessions and additional information
     * @return array
     */
    public function getActiveInfos($limit = 30, $skip = 0, $orderby = null);
}
