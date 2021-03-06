<?php
declare(strict_types=1);

namespace knotphp\module\knotcache\adapter;

use knotlib\cache\CacheInterface as KnotCacheInterface;
use knotlib\kernel\cache\CacheInterface;

class KnotCacheAdapter implements CacheInterface
{
    /** @var KnotCacheInterface */
    private $cache;

    /**
     * KnotCacheAdapter constructor.
     *
     * @param KnotCacheInterface $cache
     */
    public function __construct(KnotCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Returns cache data associated with specified key.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws
     */
    public function get(string $key)
    {
        return $this->cache->get($key);
    }

    /**
     * save cache data associated with specified key.
     *
     * @param string $key
     * @param mixed $data
     *
     * @throws
     */
    public function set(string $key, $data)
    {
        $this->cache->set($key, $data);
    }

    /**
     * Removes a cache item
     *
     * @param string $key
     *
     * @throws
     */
    public function delete( $key )
    {
        $this->cache->delete($key);
    }
}