<?php namespace Pisa\GizmoAPI\Repositories;

use Pisa\GizmoAPI\Contracts\HttpClient;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception on error
     */
    public function getHardwareId()
    {
        try {
            $response = $this->client->get('Service/HardwareId');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertString();
            $response->assertStatusCodes(200);

            return $response->getBody();

        } catch (Exception $e) {
            throw new Exception("Could not get hardware id from service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function getLicense()
    {
        try {
            $response = $this->client->get('Service/License');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Could not get license from service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function getModule()
    {
        try {
            $response = $this->client->get('Service/Module');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Could not get module from service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function getSettings()
    {
        try {
            $response = $this->client->get('Service/Settings');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Could not get settings from service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function getStatus()
    {
        try {
            $response = $this->client->get('Service/Status');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertArray();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Could not get status from service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function getTime()
    {
        try {
            $response = $this->client->get('Service/Time');
            if ($response === null) {
                throw new Exception("Response failed");
            }
            $response->assertTime();
            $response->assertStatusCodes(200);

            return strtotime($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Could not get time from service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function getVersion()
    {
        try {
            $response = $this->client->get('Service/Version');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertString();
            $response->assertStatusCodes(200);

            return $response->getBody();
        } catch (Exception $e) {
            throw new Exception("Could not get version from service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function restart()
    {
        try {
            $response = $this->client->get('Service/Restart');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            /**
             * @todo Check that restart really returns 204 empty
             */
            $response->assertEmpty();
            $response->assertStatusCodes(204);
        } catch (Exception $e) {
            throw new Exception("Could not restart service: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    public function stop()
    {
        try {
            $response = $this->client->get('Service/Stop');
            if ($response === null) {
                throw new Exception("Response failed");
            }

            /**
             * @todo Check that stop really returns 204 empty
             */
            $response->assertEmpty();
            $response->assertStatusCodes(204);
        } catch (Exception $e) {
            throw new Exception("Could not stop service: " . $e->getMessage());
        }
    }
}
