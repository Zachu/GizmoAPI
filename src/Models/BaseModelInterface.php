<?php namespace Pisa\GizmoAPI\Models;

use Pisa\GizmoAPI\Contracts\Attributable;
use Pisa\GizmoAPI\Contracts\Identifiable;

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

    /**
     * Load model attributes and mark them as saved.
     * @param  array  $attributes Attributes to be loaded
     * @return void
     */
    public function load(array $attributes);

    /**
     * Return attributes that doesn't pass the validator
     * @return array
     */
    public function getInvalid();

    /**
     * Return the validator instance
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidator();

    /**
     * Check that the model passes validation rules
     * @return boolean
     */
    public function isValid();

    /**
     * Set the validation rules
     * @param array $rules \Illuminate\Validation\Factory rules
     * @return void
     */
    public function setRules(array $rules);

    /**
     * Returns the current validation rules
     * @return array of \Illuminate\Validation\Factory rules
     */
    public function getRules();

    /**
     * Merge new rules to the current validation rules
     * @param array $rules \Illuminate\Validation\Factory rules
     * @return void
     */
    public function mergeRules(array $rules);
}
