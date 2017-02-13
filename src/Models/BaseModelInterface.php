<?php namespace Pisa\GizmoAPI\Models;

use Pisa\GizmoAPI\Contracts\Attributable;
use Pisa\GizmoAPI\Contracts\Identifiable;

interface BaseModelInterface extends Identifiable, Attributable
{
    /**
     * Cast the model to string
     * @return string
     */
    public function __toString();

    /**
     * Delete the model
     * @return BaseModel Return $this for chaining.
     */
    public function delete();

    /**
     * Check if model exists (has a primary key)
     * @return boolean
     */
    public function exists();

    /**
     * Return attributes that doesn't pass the validator
     * @return array
     */
    public function getInvalid();

    /**
     * Returns the current validation rules
     * @return array of \Illuminate\Validation\Factory rules
     */
    public function getRules();

    /**
     * Return the validator instance
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidator();

    /**
     * Check if model has saved all the changes
     * @return boolean
     */
    public function isSaved();

    /**
     * Check that the model passes validation rules
     * @return boolean
     */
    public function isValid();

    /**
     * Load model attributes and mark them as saved.
     * @param  array  $attributes Attributes to be loaded
     * @return void
     */
    public function load(array $attributes);

    /**
     * Merge new rules to the current validation rules
     * @param array $rules \Illuminate\Validation\Factory rules
     * @return void
     */
    public function mergeRules(array $rules);

    /**
     * Create or update the model
     * @return BaseModel Return $this for chaining
     */
    public function save();

    /**
     * Set the validation rules
     * @param array $rules \Illuminate\Validation\Factory rules
     * @return void
     */
    public function setRules(array $rules);

    /**
     * Runs validations for the model
     * @return boolean Return true if something fails, false otherwise
     */
    public function validate();
}
