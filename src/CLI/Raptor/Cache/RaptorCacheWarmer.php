<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 12:57
 */

namespace Bonefish\CLI\Raptor\Cache;


use Bonefish\Core\Package;

class RaptorCacheWarmer implements IRaptorCacheWarmer
{
    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var \Bonefish\Core\PackageManager
     * @inject
     */
    public $packageManager;

    const PACKAGE_STATE_CACHE = 'package.state';
    const COMMAND_SUFFIX = 'Command';

    /**
     * @var IRaptorCache
     */
    protected $cache;

    /**
     * @var \Bonefish\Reflection\Helper
     * @inject
     */
    public $parser;

    /**
     * @param IRaptorCache $cache
     */
    public function warmUp(IRaptorCache $cache)
    {
        $this->cache = $cache;

        $this->warmUpPackageStateCache();

        $this->cache->setReady(TRUE);
    }

    /**
     * @return array
     */
    protected function warmUpPackageStateCache()
    {
        $key = self::PACKAGE_STATE_CACHE;
        $cache = $this->cache->doesCacheExist($key);

        if ($cache !== FALSE)
        {
            return $cache;
        }

        $packages = $this->packageManager->getAllPackages();
        $data = array();

        foreach ($packages as $package) {
            $packageCache = $this->warmUpPackageCache($package);
            $data[$package->getVendor()][$package->getName()] = $packageCache;
        }

        $this->cache->writeCache($key, $data);

        return $data;
    }

    /**
     * @param \Bonefish\Core\Package $package
     * @return array
     */
    protected function warmUpPackageCache($package)
    {
        $key = $package->getVendor() . '.' . $package->getName();
        $cache = $this->cache->doesCacheExist($key);

        if ($cache !== FALSE)
        {
            return $cache;
        }

        $data = array();

        $commandController = $package->getController(Package::TYPE_COMMAND);
        $r = new \ReflectionClass($commandController);

        $commands = $this->parser->getSuffixMethods(self::COMMAND_SUFFIX, $r);

        foreach($commands as $command)
        {
            $data[] = $command->getName();
        }

        $this->cache->writeCache($key, $data);

        return $data;
    }

} 