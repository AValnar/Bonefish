<?php

namespace Bonefish\Core;
use Bonefish\DI\IContainer;

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
     * @var array
     */
    protected $config;

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var IContainer
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

    const TYPE_COMMAND = 'Command';

    /**
     * @param string $vendor
     * @param string $name
     * @param array $config
     */
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
            return $this->vendor . DIRECTORY_SEPARATOR . $this->name;
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
            $this->autoloader->addNamespace($this->vendor . '\\' . $this->name, $this->getPackagePath() . '/src');
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getController($name)
    {
        $class = '\\'.$this->vendor . '\\' . $this->name . '\Controller\\' . $name;
        return $this->container->get($class);
    }

    /**
     * @return array
     */
    public function getControllers()
    {
        $return = array();
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