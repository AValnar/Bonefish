<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 31.08.14
 * Time: 21:05
 */

namespace Bonefish\Core;


class Package
{

    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var \Bonefish\Autoloader\Autoloader
     * @inject
     */
    public $autoloader;

    /**
     * @var bool
     */
    protected $mapped = false;

    const TYPE_CONTROLLER = 'Controller';
    const TYPE_COMMAND = 'Command';

    public function __construct($vendor, $name)
    {
        $this->vendor = $vendor;
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $vendor
     * @return self
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    public function getPackagePath()
    {
        return  $this->environment->getFullModulePath() . '/' . $this->vendor . '/' . $this->name;
    }

    public function getPackageUrlPath()
    {
        return  $this->environment->getModulePath() . '/' . $this->vendor . '/' . $this->name;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getController($type)
    {
        $this->mapAutoloader();
        $class = $this->vendor . '\\' . $this->name . '\Controller\\' . $type;
        if (!class_exists($class)) {
            $this->autoloader->loadClass($class);
        }
        return $this->container->get($class);
    }

    public function includeBootstrap()
    {
        $path = $this->getPackagePath() . '/bootstrap.php';

        if (file_exists($path)) {
            return require $path;
        }

        return array();
    }

    public function mapAutoloader()
    {
        if ($this->mapped) {
            return;
        }

        $bootstrap = $this->includeBootstrap();

        if (isset($bootstrap['autoloader'])) {
            $this->autoloader->addNamespace($bootstrap['autoloader'][0], $this->environment->getFullModulePath() . '/' . $bootstrap['autoloader'][1]);
        }
        $this->mapped = true;
    }
} 