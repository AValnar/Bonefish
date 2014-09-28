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
     * @var array
     */
    protected $config;

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
     * @var false|array|null
     */
    protected $configuration = NULL;

    const TYPE_CONTROLLER = 'Controller';
    const TYPE_COMMAND = 'Command';

    public function __construct($vendor, $name, $config = array())
    {
        $this->vendor = $vendor;
        $this->name = $name;
        $this->config = $config;
    }

    public function __init()
    {
        $this->autoload();
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
     * @param string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->config['path'] = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if (!isset($this->config['path'])) {
            return $this->vendor.DIRECTORY_SEPARATOR.$this->name;
        }
        return $this->config['path'];
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

    /**
     * @return string
     */
    public function getPackagePath()
    {
        return $this->environment->getFullPackagePath() . '/' . $this->getPath();
    }

    /**
     * @return string
     */
    public function getPackageUrlPath()
    {
        return $this->environment->getPackagePath() . '/' . $this->getPath();
    }

    protected function autoload()
    {
        if (isset($this->config['autoload']) && $this->config['autoload']) {
            $this->autoloader->addNamespace($this->vendor . '\\' . $this->name, $this->getPackagePath());
        }
    }

    /**
     * @param string $type
     * @return string
     */
    public function getController($type)
    {
        $class = $this->vendor . '\\' . $this->name . '\Controller\\' . $type;
        return $this->container->get($class);
    }

    public function getConfiguration()
    {
        if ($this->configuration !== NULL) {
            return $this->configuration;
        }

        try {
            $path = $this->getPackagePath() . '/Configuration/Configuration.neon';
            $this->configuration = $this->configurationManager->getConfiguration($path, true);
        } catch (\Exception $e) {
            $this->configuration = false;
        }

        return $this->configuration;
    }
} 