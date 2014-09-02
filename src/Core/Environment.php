<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 31.08.14
 * Time: 21:10
 */

namespace Bonefish\Core;


class Environment
{

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $modulePath;

    /**
     * @var string
     */
    protected $configurationPath;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @param string $basePath
     * @return self
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $modulePath
     * @return self
     */
    public function setModulePath($modulePath)
    {
        $this->modulePath = $modulePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getModulePath()
    {
        return $this->modulePath;
    }

    /**
     * @return string
     */
    public function getFullModulePath()
    {
        return $this->basePath . $this->modulePath;
    }

    /**
     * @return string
     */
    public function getFullConfigurationPath()
    {
        return $this->basePath . $this->configurationPath;
    }

    /**
     * @param string $configurationPath
     * @return self
     */
    public function setConfigurationPath($configurationPath)
    {
        $this->configurationPath = $configurationPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigurationPath()
    {
        return $this->configurationPath;
    }

    /**
     * @return array
     */
    public function getAllPackages()
    {
        $return = array();
        $path = $this->getFullModulePath();
        $vendors = $this->getVendorsOrPackageNamesFromPath($path);
        foreach ($vendors as $vendor) {
            $packages = $this->getVendorsOrPackageNamesFromPath($path . '/' . $vendor);
            foreach ($packages as $package) {
                $return[] = $this->createPackage($vendor, $package);
            }
        }
        return $return;
    }

    /**
     * @param string $vendor
     * @param string $package
     * @return \Bonefish\Core\Package
     */
    public function createPackage($vendor, $package)
    {
        return $this->container->create('\Bonefish\Core\Package', array($vendor, $package));
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getVendorsOrPackageNamesFromPath($path)
    {
        $return = array();
        $iterator = new \DirectoryIterator($path);
        foreach ($iterator as $element) {
            if (!$element->isDir() || $element->isDot()) continue;
            $return[] = $element->__toString();
        }
        return $return;
    }
} 