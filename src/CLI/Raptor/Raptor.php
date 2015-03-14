<?php


namespace Bonefish\CLI\Raptor;
use Bonefish\AbstractTraits\Parameters;
use Bonefish\CLI\ICLI;
use Bonefish\CLI\Raptor\Cache\IRaptorCache;
use Bonefish\CLI\Raptor\Cache\RaptorCache;

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
class Raptor implements ICLI
{
    use Parameters;

    /**
     * @var \Bonefish\CLI\Raptor\Cache\IRaptorCache
     * @inject
     */
    public $cache;

    /**
     * @var \Bonefish\CLI\Raptor\Cache\IRaptorCacheWarmer
     * @inject
     */
    public $cacheWarmer;

    /**
     * Main handler
     */
    public function run()
    {
        $this->warmCache($this->cache);

        if (!$this->cache->isReady())
        {
            throw new \RuntimeException('Raptor was not able to get/create it\'s cache data!');
        }
    }

    /**
     * Execute an action
     *
     * @param \Bonefish\Core\Package $package
     * @param string $action
     * @param array $parameters
     */
    public function execute($package, $action, $parameters = array())
    {
        // TODO: Implement execute() method.
    }

    /**
     * Explain an action
     *
     * @param \Bonefish\Core\Package $package
     * @param string $action
     */
    public function explain($package, $action)
    {
        // TODO: Implement explain() method.
    }

    /**
     * Prepare Cache and create if it does not exist yet
     * @param IRaptorCache $cache Cache to warm up
     */
    protected function warmCache(IRaptorCache $cache)
    {
        $this->cacheWarmer->warmUp($cache);
    }

} 