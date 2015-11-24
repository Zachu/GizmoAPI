<?php namespace Pisa\Api\Gizmo\Models;

use Pisa\Api\Gizmo\Contracts\Attributable;
use Pisa\Api\Gizmo\Contracts\Identifiable;

interface BaseModelInterface extends Identifiable, Attributable
{
    /**
     * Delete the model
     * @return BaseModel Return $this for chaining.
     * @throws Exception on error.
     */
    public function delete();

    /**
     * Check if model exists (has a primary key)
     * @return boolean
     */
    public function exists();

    /**
     * Check if model has saved all the changes
     * @return boolean
     */
    public function isSaved();

    /**
     * Create or update the model
     * @return BaseModel Return $this for chaining
     * @throws Exception on error.
     */
    public function save();
}
