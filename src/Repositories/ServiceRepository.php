<?php namespace Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Exceptions\InternalException;

class ServiceRepository implements ServiceRepositoryInterface
{
    /** @var HttpClient */
    protected $client;

    /**
     * @param HttpClient $client Implemention of http client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Exception on error
     */
    public function getHardwareId()
    {
        $response = $this->client->get('Service/HardwareId');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertString();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function getLicense()
    {
        $response = $this->client->get('Service/License');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function getModule()
    {
        $response = $this->client->get('Service/Module');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function getSettings()
    {
        $response = $this->client->get('Service/Settings');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function getStatus()
    {
        $response = $this->client->get('Service/Status');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertArray();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function getTime()
    {
        $response = $this->client->get('Service/Time');
        if ($response === null) {
            throw new InternalException("Response failed");
        }
        $response->assertTime();
        $response->assertStatusCodes(200);

        return strtotime($response->getBody());
    }

    /**
     * @throws \Exception on error
     */
    public function getVersion()
    {
        $response = $this->client->get('Service/Version');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertString();
        $response->assertStatusCodes(200);

        return $response->getBody();
    }

    /**
     * @throws \Exception on error
     */
    public function restart()
    {
        $response = $this->client->get('Service/Restart');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        /**
         * @todo Check that restart really returns 204 empty
         */
        $response->assertEmpty();
        $response->assertStatusCodes(204);
    }

    /**
     * @throws \Exception on error
     */
    public function stop()
    {
        $response = $this->client->get('Service/Stop');
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        /**
         * @todo Check that stop really returns 204 empty
         */
        $response->assertEmpty();
        $response->assertStatusCodes(204);
    }
}
