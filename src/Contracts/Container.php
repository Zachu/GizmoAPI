<?php namespace Pisa\Api\Gizmo\Contracts;

interface Container
{
    public function bind($abstract, $concrete = null, $shared = false);
    public function singleton($abstract, $concrete = null);
    public function make($abstract, array $parameters = []);
}
