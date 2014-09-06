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
     * @var string
     */
    protected $cachePath;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var \Bonefish\Core\Package
     */
    protected $package;

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
     * @param string $cachePath
     * @return self
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * @return string
     */
    public function getFullCachePath()
    {
        return $this->basePath . $this->cachePath;
    }

    /**
     * @param \Bonefish\Core\Package $package
     * @return self
     */
    public function setPackage($package)
    {
        $this->package = $package;
        return $this;
    }

    /**
     * @return \Bonefish\Core\Package
     */
    public function getPackage()
    {
        return $this->package;
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