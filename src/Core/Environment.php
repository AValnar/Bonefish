<?php

namespace Bonefish\Core;

/**
 * Copyright (C) 2014  Alexander Schmidt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-08-31
 * @package Bonefish\Core
 */
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
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var \Bonefish\Core\PackageManager
     * @inject
     */
    public $packageManager;

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
     * @deprecated Use Bonefish\Core\PackageManager instead
     */
    public function setPackageStates($packageState)
    {
        $this->packageManager->setPackageStates($packageState);
        return $this;
    }

    /**
     * @return array
     * @deprecated Use Bonefish\Core\PackageManager instead
     */
    public function getPackageStates()
    {
        return $this->packageManager->getPackageStates();
    }

    /**
     * @param string $vendor
     * @param string $package
     * @return \Bonefish\Core\Package
     * @deprecated Use Bonefish\Core\PackageManager instead
     */
    public function createPackage($vendor, $package)
    {
        return $this->packageManager->createPackage($vendor, $package);
    }

    /**
     * @return array
     * @deprecated Use Bonefish\Core\PackageManager instead
     */
    public function getAllPackages()
    {
        return $this->packageManager->getAllPackages();
    }
}