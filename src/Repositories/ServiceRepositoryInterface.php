<?php namespace Pisa\GizmoAPI\Repositories;

interface ServiceRepositoryInterface
{
    /**
     * Returns hardware id
     * @return string
     */
    public function getHardwareId();

    /**
     * Returns license information
     * @return array
     */
    public function getLicense();

    /**
     * Returns the service module information
     * @return array
     */
    public function getModule();

    /**
     * Returns the service settings
     * @return array
     */
    public function getSettings();

    /**
     * Returns status of the service
     * @return array
     */
    public function getStatus();

    /**
     * Returns current system time.
     * @return int Unix timestamp
     */
    public function getTime();

    /**
     * Returns the service version
     * @return string
     */
    public function getVersion();

    /**
     * Restarts the service
     * @return void
     */
    public function restart();

    /**
     * Stops the service
     * @return void
     */
    public function stop();
}
