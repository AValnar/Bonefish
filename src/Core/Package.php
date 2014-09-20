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
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var null|\Respect\Config\Container
     */
    protected $configuration = NULL;

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
        return  $this->environment->getFullPackagePath() . '/' . $this->vendor . '/' . $this->name;
    }

    public function getPackageUrlPath()
    {
        return  $this->environment->getPackagePath() . '/' . $this->vendor . '/' . $this->name;
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

    public function getConfiguration()
    {
        if ($this->configuration != NULL) {
            return $this->configuration;
        }

        try {
            $path = $this->getPackagePath() . '/Configuration/Configuration.ini';
            $this->configuration = $this->configurationManager->getConfiguration($path, true);
        } catch (\Exception $e) {
            $this->configuration = false;
        }

        return $this->configuration;
    }

    public function mapAutoloader()
    {
        if ($this->mapped) {
            return;
        }

        $config = $this->getConfiguration();

        if ($config && isset($config->autoload) && $config->autoload) {
            $this->autoloader->addNamespace($config->classPrefix, $this->environment->getFullPackagePath() . '/' . $config->classPath);
        }

        $this->mapped = true;
    }
} 