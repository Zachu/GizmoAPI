<?php namespace Pisa\Api\Gizmo\Models;

use Pisa\Api\Gizmo\Contracts\Attributable;
use Pisa\Api\Gizmo\Contracts\Identifiable;

interface BaseModelInterface extends Identifiable, Attributable
{
    //public function load($id); <-- use the repository
    public function delete();
    public function exists();
    public function isSaved();
    public function save();
}
