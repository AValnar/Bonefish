<?php

namespace Bonefish\Core;

use Bonefish\Injection\ContainerInterface;
use Bonefish\Utility\ConfigurationManager;

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
 * @date       2015-03-13
 * @package Bonefish
 */
class PackageManager
{

    /**
     * @var ContainerInterface
     * @Bonefish\Inject
     */
    public $container;

    /**
     * @var ConfigurationManager
     * @Bonefish\Inject
     */
    public $configurationManager;

    /**
     * @var array
     */
    protected $packageState = null;

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
        if ($this->packageState === null) {
            $this->packageState = $this->configurationManager->getConfiguration('Packages.neon');
        }
        return $this->packageState;
    }

    /**
     * @param string $vendor
     * @param string $package
     * @return \Bonefish\Core\Package
     * @throws \InvalidArgumentException
     */
    public function createPackage($vendor, $package)
    {
        if (!$this->isPackageInstalledByVendorAndPackageName($vendor, $package)) {
            throw new \InvalidArgumentException('Package is not set up or does not exist!');
        }

        return $this->container->get('\Bonefish\Core\Package', [$vendor, $package]);
    }

    /**
     * @param string $vendor
     * @return bool
     */
    public function isPackageInstalledByVendor($vendor)
    {
        $state = $this->getPackageStates();

        return isset($state[$vendor]);
    }

    /**
     * @param string $vendor
     * @param string $package
     * @return bool
     */
    public function isPackageInstalledByVendorAndPackageName($vendor, $package)
    {
        $state = $this->getPackageStates();

        return isset($state[$vendor][$package]);
    }

    /**
     * @return \Bonefish\Core\Package[]
     */
    public function getAllPackages()
    {
        $states = $this->getPackageStates();
        $return = [];
        foreach ($states as $vendor => $packages) {
            foreach ($packages as $package => $config) {
                $return[] = $this->createPackage($vendor, $package);
            }
        }
        return $return;
    }

} 