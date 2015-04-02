<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 01.04.2015
 * Time: 18:19
 */

namespace Bonefish\Cache\Factory;


use Bonefish\AbstractTraits\DirectoryCreator;
use Bonefish\Cache\Cache;
use Bonefish\Core\Environment;
use Bonefish\DI\IContainer;
use Bonefish\Factory\IFactory;
use Nette\Caching\Storages\FileStorage;
use Nette\Reflection\AnnotationsParser;

class CacheFactory implements IFactory
{

    use DirectoryCreator;

    /**
     * @var IContainer
     * @Bonefish\Inject
     */
    public $container;

    /**
     * @var Environment
     * @Bonefish\Inject
     */
    public $environment;

    /**
     * Return an object with fully injected dependencies
     *
     * @param array $parameters
     * @return mixed
     */
    public function create(array $parameters = array())
    {
        $path = $this->environment->getFullCachePath();
        $this->createDir($path);
        $storageClass = '\Nette\Caching\Storages\FileStorage';

        if (!$this->container->exists($storageClass)) {
            $storage = new FileStorage($path);
            $this->container->add('\Nette\Caching\Storages\FileStorage', $storage);
            AnnotationsParser::setCacheStorage($storage);
        } else {
            $storage = $this->container->get('\Nette\Caching\Storages\FileStorage');
        }

        $cache = new \Nette\Caching\Cache($storage, isset($parameters['key']) ? $parameters['key'] : NULL);

        return new Cache($cache);
    }
}