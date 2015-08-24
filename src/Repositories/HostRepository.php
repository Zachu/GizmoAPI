<?php namespace Pisa\Api\Gizmo\Repositories;

use Exception;

//@todo try catch all this shit. I think Models/Host.php looks like promising format

class HostRepository extends BaseRepository implements HostRepositoryInterface, BaseRepositoryInterface
{
    protected $model = 'Host';

    public function all($limit = 30, $skip = 0, $orderBy = null)
    {
        $options = ['$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $result = $this->client->get('Hosts/Get', $options)->getBody();

        if (is_array($result)) {
            $hosts = $this->makeArray($result);
        } else {
            throw new Exception("Requesting array of hosts, got " . gettype($result));
            //@todo error handling
        }

        return $hosts;
    }

    /*
    public function create(BaseModelInterface $model)
    {
    try {
    if (!$model instanceof HostInterface) {
    throw new Exception("Given model is not host");
    }
    throw new Exception("New host cannot be created via API. Host is created by connecting new host client to the server service");
    } catch (Ecception $e) {
    throw new Exception("Unable to create host: ".$e->getMessage());
    }
    }
     */

    /*
    public function delete(BaseModelInterface $model)
    {
    try {
    if (!$model instanceof HostInterface) {
    throw new Exception("Given model is not host");
    }
    throw new Exception("Host cannot be deleted via API. Host is deleted by via the server service");
    } catch (Ecception $e) {
    throw new Exception("Unable to delete host: ".$e->getMessage());
    }
    }
     */

    /*
    public function update(BaseModelInterface $model)
    {
    try {
    if (!$model instanceof HostInterface) {
    throw new Exception("Given model is not host");
    }
    throw new Exception("Host cannot be updated via API. Host is updated via the server service");
    } catch (Ecception $e) {
    throw new Exception("Unable to update host: ".$e->getMessage());
    }
    }
     */

    public function findBy(array $criteria, $caseSensitive = false, $limit = 30, $skip = 0, $orderBy = null)
    {
        $filter = $this->criteriaToFilter($criteria, $caseSensitive);
        $options = ['$filter' => $filter, '$skip' => $skip, '$top' => $limit];
        if ($orderBy !== null) {
            $options['$orderby'] = $orderBy;
        }

        $result = $this->client->get('Hosts/Get', $options)->getBody();
        //@todo error handling
        //@todo check return values

        return $this->makeArray($result);
    }

    public function findOneBy(array $criteria, $caseSensitive = false)
    {
        $filter = $this->criteriaToFilter($criteria, $caseSensitive);
        $result = $this->client->get('Hosts/Get', ['$filter' => $filter, '$top' => 1])->getBody();

        if (!is_array($result)) {
            throw new Exception("Requesting array of hosts, got " . gettype($result));
            //@todo error handling
        } elseif (empty($result)) {
            return null;
        } else {
            return $this->make(reset($result));
        }
    }

    public function get($id)
    {
        $result = $this->client->get('Hosts/Get/' . (int) $id)->getBody();
        if ($result === null) {
            return $result;
        } elseif (!is_array($result)) {
            throw new Exception("Requesting host model, got " . gettype($result));
            //@todo error handling
        }

        return $this->make($result);
    }

    public function getByNumber($hostNumber)
    {
        $result = $this->client->get('Hosts/GetByNumber', ['hostNumber' => $hostNumber])->getBody();

        if (!is_array($result)) {
            throw new Exception("Requesting array of hosts, got " . gettype($result));
            //@todo error handling
        }

        return $this->makeArray($result);
    }

    public function has($id)
    {
        return ($this->get($id) !== null ? true : false);
    }
}
