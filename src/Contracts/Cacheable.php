<?php namespace Pisa\Api\Gizmo\Contracts;

use Doctrine\Common\Cache\Cache;

interface Cacheable
{
    public function setCache(Cache $cache);
    public function containsCached();
    public function fetchCached();
    public function saveCached($lifetime);
    public function deleteCached();
}
