<?php namespace Pisa\Api\Gizmo\Contracts;

use Doctrine\Common\Cache\Cache;

trait CacheableTrait
{
    protected $cache;

    protected function getCacheId()
    {
        return get_class($this) . '-' . $this->getPrimaryKeyValue();
    }

    public function setCache(Cache $cache = null)
    {
        $this->cache = $cache;
    }

    public function containsCached()
    {
        if (!isset($this->cache)) {
            return false;
        } else {
            return $this->cache->contains($this->getCacheId());
        }
    }

    public function fetchCached()
    {
        if (!isset($this->cache)) {
            return false;
        } else {
            return $this->cache->fetch($this->getCacheId());
        }
    }

    public function saveCached($lifetime)
    {
        if (!isset($this->cache)) {
            return false;
        } else {
            return $this->cache->save($this->getCacheId(), $this, $lifetime);
        }
    }

    public function deleteCached()
    {
        if (!isset($this->cache)) {
            return false;
        } else {
            return $this->cache->delete($this->getCacheId());
        }
    }
}
