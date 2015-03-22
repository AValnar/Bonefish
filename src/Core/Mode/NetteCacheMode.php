<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 09:05
 */

namespace Bonefish\Core\Mode;


class NetteCacheMode extends LoadAliasMode
{
    const MODE = 'NetteCacheMode';

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        if ($this->isModeStarted(self::MODE)) return;

        $path = $this->environment->getFullCachePath();
        $this->createDir($path);
        $storage = new \Nette\Caching\Storages\FileStorage($path);
        $cache = new \Nette\Caching\Cache($storage);
        $this->container->add('\Nette\Caching\Cache', $cache);
        $this->container->add('\Nette\Caching\Storages\FileStorage', $storage);
        \Nette\Reflection\AnnotationsParser::setCacheStorage($storage);

        $this->setModeStarted(self::MODE);
    }

    /**
     * @param string $path
     */
    protected function createDir($path)
    {
        if (!file_exists($path)) {
            mkdir($path);
        }
    }
} 