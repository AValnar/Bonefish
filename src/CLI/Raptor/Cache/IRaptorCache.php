<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 12:53
 */

namespace Bonefish\CLI\Raptor\Cache;


interface IRaptorCache {

    /**
     * @param string $cache
     * @param array $data
     */
    public function writeCache($cache, array $data = array());

    /**
     * @param string $cache
     * @return array
     */
    public function getCache($cache);

    /**
     * @return boolean
     */
    public function isReady();

    /**
     * @param boolean $ready
     * @return self
     */
    public function setReady($ready);

    /**
     * @param string $cache
     * @return bool
     */
    public function doesCacheExist($cache);
} 