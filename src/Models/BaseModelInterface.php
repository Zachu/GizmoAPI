<?php namespace Pisa\Api\Gizmo\Models;

interface BaseModelInterface
{
    //public function load($id); <-- use the repository
    public function delete();
    public function exists();
    public function fill(array $attributes, $skipChecks);
    public function getAttribute($key);
    public function getAttributes();
    public function getPrimaryKey();
    public function getPrimaryKeyValue();
    public function isSaved();
    public function setAttribute($key, $value);
    public function save();
}
