<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 12:57
 */

namespace Bonefish\CLI\Raptor\Cache;


interface IRaptorCacheWarmer
{

    /**
     * @param IRaptorCache $cache
     */
    public function warmUp(IRaptorCache $cache);
} 