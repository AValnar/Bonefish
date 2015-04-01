<?php

namespace Bonefish\Core;

use Bonefish\AbstractTraits\DirectoryCreator;
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
 * @date       2014-09-21
 * @package Bonefish\Core
 */
class Kernel
{
    use DirectoryCreator;

    /**
     * @var IContainer
     * @inject
     */
    public $container;

    /**
     * @var ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var Environment
     * @inject
     */
    public $environment;

    /**
     * @var string
     */
    protected static $baseDir;

    /**
     * @var array
     */
    protected $basicConfiguration = NULL;

    /**
     * @return array
     */
    public function getBasicConfiguration()
    {
        if ($this->basicConfiguration === NULL)
        {
            $this->basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');
        }

        return $this->basicConfiguration;
    }

    /**
     * @return string
     */
    public static function getBaseDir()
    {
        return self::$baseDir;
    }

    /**
     * @param string $baseDir
     */
    public static function setBaseDir($baseDir)
    {
        self::$baseDir = $baseDir;
    }

    public function registerImplementations()
    {
        $basicConfiguration = $this->getBasicConfiguration();

        foreach ($basicConfiguration['implementations'] as $interface => $implementation) {
            $this->container->setInterfaceImplementation($implementation, $interface);
        }
    }

    public function startTracy()
    {
        $logPath = $this->environment->getFullLogPath();
        $this->createDir($logPath);

        if ($this->basicConfiguration['global']['develoment']) {
            \Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, $logPath);
        } else {
            \Tracy\Debugger::enable(\Tracy\Debugger::PRODUCTION, $logPath);
        }

        \Tracy\Debugger::$strictMode = TRUE;
    }

    public function start()
    {
        $server = $_SERVER;
        $url = \League\Url\UrlImmutable::createFromServer($server);
        /** @var \Bonefish\Router\FastRoute $router */
        $router = $this->container->create('\Bonefish\Router\FastRoute', array($url));
        $this->container->add('\Bonefish\Router\FastRoute', $router);
        $router->route();
    }

} 