<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 01.04.2015
 * Time: 18:12
 */

namespace Bonefish\Cache;


interface ICache
{
    /**
     * @param string $key
     * @param mixed $data
     * @param int $lifeTime
     * @return bool
     */
    public function save($key, $data, $lifeTime = 0);

    /**
     * @param string $key
     * @return mixed
     */
    public function fetch($key);

    /**
     * @param string $key
     * @return bool
     */
    public function contains($key);

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key);

}