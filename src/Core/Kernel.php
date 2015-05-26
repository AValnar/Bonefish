<?php

namespace Bonefish\Core;

use Bonefish\AbstractTraits\DirectoryCreator;
use Bonefish\Autoloader\Autoloader;
use Bonefish\DI\IContainer;
use Bonefish\Router\FastRoute;
use Nette\Reflection\AnnotationsParser;
use Tracy\Debugger;

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
     * @Bonefish\Inject
     */
    public $container;

    /**
     * @var ConfigurationManager
     * @Bonefish\Inject
     */
    public $configurationManager;

    /**
     * @var Environment
     * @Bonefish\Inject
     */
    public $environment;

    /**
     * @var Autoloader
     * @Bonefish\Inject
     */
    public $autoloader;

    /**
     * @var FastRoute
     * @Bonefish\Inject
     */
    public $router;

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

    public function lowLevelBoot()
    {
        $this->registerImplementations();
        $this->startAutoloader();
        $basicConfiguration = $this->getBasicConfiguration();
        $this->environment->setDevMode($basicConfiguration['global']['develoment']);
        AnnotationsParser::$autoRefresh = $this->environment->isDevMode();
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

        if ($this->environment->isDevMode()) {
            Debugger::enable(Debugger::DEVELOPMENT, $logPath);
        } else {
            Debugger::enable(Debugger::PRODUCTION, $logPath);
        }

        Debugger::$strictMode = TRUE;
    }

    public function startAutoloader()
    {
        $this->autoloader->register();
    }

    public function start()
    {
        $server = $_SERVER;
        $url = \League\Url\UrlImmutable::createFromServer($server);
        $this->router->setUrl($url);
        $this->router->route();
    }

} 