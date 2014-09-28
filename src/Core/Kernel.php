<?php
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
 * @date       2014-09-20
 * @package Bonefish
 */

namespace Bonefish\Core;


class Kernel
{
    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var string
     */
    private $baseDir;

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function __init()
    {
        $this->initAutolaoder();
        $this->initEnvironment($this->baseDir);
        $this->initCache();
        $this->initLatte();
        $this->initDatabase();
    }

    protected function initAutolaoder()
    {
        $autoloader = new \Bonefish\Autoloader\Autoloader();
        $autoloader->register();
        $this->container->add('\Bonefish\Autoloader\Autoloader', $autoloader);
    }

    protected function initEnvironment($baseDir)
    {
        /** @var \Bonefish\Core\Environment $environment */
        $environment = $this->container->get('\Bonefish\Core\Environment')
            ->setBasePath($baseDir)
            ->setConfigurationPath('/Configuration');

        $basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');
        $environment->setPackagePath($basicConfiguration['global']['packagePath'])
            ->setCachePath($basicConfiguration['global']['cachePath']);
    }

    protected function initCache()
    {
        /** @var \Bonefish\Core\Environment $environment */
        $environment = $this->container->get('\Bonefish\Core\Environment');
        $path = $environment->getFullCachePath();
        $this->createDir($path);
        $storage = new \Nette\Caching\Storages\FileStorage($path);
        $cache = new \Nette\Caching\Cache($storage);
        $this->container->add('\Nette\Caching\Cache', $cache);
        $this->container->add('\Nette\Caching\Storages\FileStorage', $storage);
    }

    protected function initLatte()
    {
        /** @var \Latte\Engine $latte */
        $latte = $this->container->get('\Latte\Engine');
        /** @var \Bonefish\Core\Environment $environment */
        $environment = $this->container->get('\Bonefish\Core\Environment');
        $basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');
        $path = $environment->getFullCachePath() . $basicConfiguration['global']['lattePath'];
        $this->createDir($path);
        $latte->setTempDirectory($path);
    }

    protected function initDatabase()
    {
        try {
            $dbConfig = $this->configurationManager->getConfiguration('Configuration.neon');
            $connection = new \Nette\Database\Connection(
                $dbConfig['database']['db_driver'] . ':host=' . $dbConfig['database']['db_host'] . ';dbname=' . $dbConfig['database']['db_name'],
                $dbConfig['database']['db_user'],
                $dbConfig['database']['db_pw'],
                array('lazy' => FALSE)
            );
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        $context = new \Nette\Database\Context($connection, NULL, $this->container->get('\Nette\Caching\Storages\FileStorage'));
        $this->container->add('\Nette\Database\Context', $context);
    }

    public function startTracy()
    {
        $basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');
        if ($basicConfiguration['global']['develoment']) {
            \Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT);
        } else {
            \Tracy\Debugger::enable(\Tracy\Debugger::PRODUCTION);
        }
        \Tracy\Debugger::$strictMode = TRUE;
    }

    public function start()
    {
        $url = \League\Url\UrlImmutable::createFromServer($_SERVER);
        $router = $this->container->create('Bonefish\Router\Router', array($url));
        $router->route();
    }

    private function createDir($path)
    {
        if (!file_exists($path)) {
            mkdir($path);
        }
    }
} 