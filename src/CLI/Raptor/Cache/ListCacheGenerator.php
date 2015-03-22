<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 17.03.2015
 * Time: 07:06
 */

namespace Bonefish\CLI\Raptor\Cache;


use Bonefish\CLI\Raptor\Command\ICommand;
use Bonefish\Core\Package;

class ListCacheGenerator
{
    /**
     * @var \Bonefish\Core\PackageManager
     * @inject
     */
    public $packageManager;

    /**
     * @var \Nette\Caching\Cache
     * @inject
     */
    public $cache;

    /**
     * @var \Bonefish\Reflection\Helper
     * @inject
     */
    public $reflectorHelper;

    public function generate($key)
    {
        $list = $this->cache->load($key);

        if ($list !== NULL) {
            return $list;
        }

        // Generate Cache

        $packages = $this->packageManager->getAllPackages();

        $list = array();

        foreach ($packages as $package) {
            $controllerCommands = array();

            $commandController = $package->getController(Package::TYPE_COMMAND);
            $reflection = new \ReflectionClass($commandController);
            $commands = $this->reflectorHelper->getSuffixMethods(ICommand::COMMAND_SUFFIX, $reflection);

            if (!empty($commands)) {
                foreach ($commands as $command) {
                    $controllerCommands[] = str_replace(ICommand::COMMAND_SUFFIX, '', $command->getName());
                }
            }

            $list[$package->getVendor()][$package->getName()] = $controllerCommands;
        }

        $this->cache->save($key, $list);

        return $list;
    }
} 