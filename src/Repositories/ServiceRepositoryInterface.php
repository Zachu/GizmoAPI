<?php namespace Pisa\GizmoAPI\Repositories;

interface ServiceRepositoryInterface
{
    public function getTime();
    public function stop();
    public function restart();
    public function getStatus();
    public function getVersion();
    public function getModule();
    public function getLicense();
    public function getHardwareId();
    public function getSettings();
}
