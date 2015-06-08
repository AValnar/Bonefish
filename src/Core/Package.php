<?php

namespace Bonefish\Core;

use Bonefish\Injection\ContainerInterface;
use Bonefish\Utility\Environment;
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
 * @date       2014-08-31
 * @package Bonefish\Core
 */
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
     * @var Environment
     * @Bonefish\Inject
     */
    public $environment;

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
     * @var false|array
     */
    protected $configuration = null;

    const TYPE_COMMAND = 'Command';

    /**
     * @param string $vendor
     * @param string $name
     */
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
     * @return string
     */
    public function getPath()
    {
        return $this->vendor . '/' . $this->name;
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


    /**
     * @param string $name
     * @param bool $returnObject
     * @return mixed
     */
    public function getController($name, $returnObject = true)
    {
        $class = '\\'.$this->vendor . '\\' . $this->name . '\Controller\\' . $name;
        if (!$returnObject) return $class;
        return $this->container->get($class);
    }

    /**
     * @return array
     */
    public function getControllers()
    {
        $return = [];
        $iterator = new \DirectoryIterator($this->getPackagePath() . '/Controller');
        foreach ($iterator as $file) {
            if ($this->isControllerPossibleController($file)) {
                $name = substr($file->getFilename(), 0, -4);
                $return[] = $this->getController($name);
            }
        }
        return $return;
    }

    /**
     * @param \DirectoryIterator $file
     * @return bool
     */
    protected function isControllerPossibleController($file)
    {
        if ($file->getExtension() != 'php') return false;
        if ($file->getFilename() == self::TYPE_COMMAND . '.php') return false;
        return true;
    }

    /**
     * @return array|bool
     */
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