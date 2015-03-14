<?php

namespace Bonefish\CLI\Raptor\Cache;

/**
 * Copyright (C) 2014  Alexander Schmidt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2015-03-14
 * @package Bonefish\CLI\Raptor
 */
class RaptorCache implements IRaptorCache
{

    /**
     * @var bool
     */
    protected $ready;

    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    public function __construct()
    {
        $this->ready = FALSE;
    }

    public function getCachePath()
    {
        $path = $this->environment->getFullCachePath() . DIRECTORY_SEPARATOR . 'Raptor' . DIRECTORY_SEPARATOR;
        if (is_dir($path)) {
            mkdir($path);
        }
        return $path;
    }

    /**
     * @param string $cache
     * @param array $data
     */
    public function writeCache($cache, array $data = array())
    {
        $path = $this->getCachePath() . $cache;
        $this->configurationManager->writeConfiguration($path, $data, TRUE);
    }

    /**
     * @param string $cache
     * @return array
     */
    public function getCache($cache)
    {
        $path = $this->getCachePath() . $cache;
        return $this->configurationManager->getConfiguration($path, TRUE);
    }

    /**
     * @return boolean
     */
    public function isReady()
    {
        return $this->ready;
    }

    /**
     * @param boolean $ready
     * @return self
     */
    public function setReady($ready)
    {
        $this->ready = $ready;
        return $this;
    }

    /**
     * @param string $cache
     * @return bool
     */
    public function doesCacheExist($cache)
    {
        try {
            return $this->getCache($cache);
        } catch (\Exception $e) {
            return false;
        }
    }
} 