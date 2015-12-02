<?php namespace Pisa\GizmoAPI\Repositories;

interface ServiceRepositoryInterface
{
    /**
     * Returns current system time.
     * @return int Unix timestamp
     */
    public function getTime();

    /**
     * Stops the service
     * @return void
     */
    public function stop();

    /**
     * Restarts the service
     * @return void
     */
    public function restart();

    /**
     * Returns status of the service
     * @return array
     */
    public function getStatus();

    /**
     * Returns the service version
     * @return string
     */
    public function getVersion();

    /**
     * Returns the service module information
     * @return array
     */
    public function getModule();

    /**
     * Returns license information
     * @return array
     */
    public function getLicense();

    /**
     * Returns hardware id
     * @return string
     */
    public function getHardwareId();

    /**
     * Returns the service settings
     * @return array
     */
    public function getSettings();
}
