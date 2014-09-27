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
    protected $packagePath;

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
    protected $currentPackage;

    /**
     * @var array
     */
    protected $packageState = array();

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
     * @param string $packagePath
     * @return self
     */
    public function setPackagePath($packagePath)
    {
        $this->packagePath = $packagePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getPackagePath()
    {
        return $this->packagePath;
    }

    /**
     * @return string
     */
    public function getFullPackagePath()
    {
        return $this->basePath . $this->packagePath;
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
     * @return string
     */
    public function getFullConfigurationPath()
    {
        return $this->basePath . $this->configurationPath;
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
     * @param \Bonefish\Core\Package $currentPackage
     * @return self
     */
    public function setPackage($currentPackage)
    {
        $this->currentPackage = $currentPackage;
        return $this;
    }

    /**
     * @return \Bonefish\Core\Package
     */
    public function getPackage()
    {
        return $this->currentPackage;
    }

    /**
     * @param array $packageState
     * @return self
     */
    public function setPackageStates($packageState)
    {
        $this->packageState = $packageState;
        return $this;
    }

    /**
     * @return array
     */
    public function getPackageStates()
    {
        if (empty($this->packageState)) {
            $this->packageState = require $this->getFullConfigurationPath() . '/Packages.state.php';
        }
        return $this->packageState;
    }

    /**
     * @param string $vendor
     * @param string $package
     * @return \Bonefish\Core\Package
     */
    public function createPackage($vendor, $package)
    {
        $packages = $this->getPackageStates();
        $config = isset($packages[$vendor][$package]) ? $packages[$vendor][$package] : array();
        return $this->container->create('\Bonefish\Core\Package', array($vendor, $package, $config));
    }

    /**
     * @return array
     */
    public function getAllPackages()
    {
        $states = $this->getPackageStates();
        $return = array();
        foreach ($states as $vendor => $packages) {
            foreach ($packages as $package => $config) {
                $return[] = $this->createPackage($vendor, $package);
            }
        }
        return $return;
    }

} 