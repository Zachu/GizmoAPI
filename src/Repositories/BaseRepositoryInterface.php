<?php namespace Pisa\GizmoAPI\Repositories;

interface BaseRepositoryInterface
{
    /**
     * Get all model instances from repository
     *
     * @param  integer $limit   Limit the number of fetched instances. Defaults to 30.
     * @param  integer $skip    Skip number of instances (i.e. fetch the next page). Defaults to 0.
     * @param  string  $orderBy Column to order the results with.
     * @return array            Returns array of model instances.
     */
    public function all($limit = 30, $skip = 0, $orderBy = null);

    /**
     * Finds model instances by parameters
     *
     * @param  array   $criteria      Array of criteria to search for.
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false.
     * @param  integer $limit         Limit the number of fetched instances. Defaults to 30.
     * @param  integer $skip          Skip number of instances (i.e. fetch the next page). Defaults to 0.
     * @param  string  $orderBy       Column to order the results with.
     * @return array                  Returns array of model instances.
     */
    public function findBy(
        array $criteria,
        $caseSensitive = false,
        $limit = 30,
        $skip = 0,
        $orderBy = null
    );

    /**
     * Find one model entry by parameters
     *
     * @param  array   $criteria      Array of criteria to search for
     * @param  boolean $caseSensitive Search for case sensitive parameters. Defaults to false
     * @return Model|null             Returns the first model entry found on current criteria. Returns null if none is found.
     */
    public function findOneBy(array $criteria, $caseSensitive = false);

    /**
     * Get model by id
     *
     * @param  integer $id Id of the model entry.
     * @return Model|null   Returns model. If no model is found, returns null.
     */
    public function get($id);

    /**
     * Check if model entry exists.
     *
     * @param  integer $id Id of the model entry.
     * @return boolean
     */
    public function has($id);

    /**
     * Make a new model
     *
     * @param  array  $attributes Attributes for the model to be made
     * @return Model              Returns model.
     */
    public function make(array $attributes);

    /**
     * Turn array of criteria into an OData filter
     *
     * @param  array   $criteria      Array of criteria
     * @param  boolean $caseSensitive Is the search supposed to be case sensitive. Defaults to false.
     * @return string                 Returns string to be put on the OData $filter
     * @internal
     */
    public static function criteriaToFilter(array $criteria, $caseSensitive = false);
}
