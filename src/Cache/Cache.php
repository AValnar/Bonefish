<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 01.04.2015
 * Time: 18:10
 */

namespace Bonefish\Cache;


class Cache implements ICache
{

    /**
     * @var \Nette\Caching\Cache
     */
    protected $cache;

    /**
     * @param \Nette\Caching\Cache $cache
     */
    public function __construct(\Nette\Caching\Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @param int $lifeTime
     * @param array $dependencies
     * @return mixed
     */
    public function save($key, $data, $lifeTime = 0, array $dependencies = NULL)
    {
        if ($lifeTime > 0 && $dependencies === NULL) {
            $dependencies = array(\Nette\Caching\Cache::EXPIRE => $lifeTime. ' seconds');
        }

        return $this->cache->save($key, $data, $dependencies);
    }

    /**
     * @param string $key
     * @param callable $fallback
     * @return mixed
     */
    public function fetch($key, $fallback = NULL)
    {
        return $this->cache->load($key, $fallback);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function contains($key)
    {
        return $this->fetch($key) === NULL;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        $this->cache->remove($key);
        return TRUE;
    }
}