<?php

namespace Bonefish;

use Composer\Script\Event;

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
class Composer {

    /**
     * @var \Bonefish\DependencyInjection\Container
     */
    private static $container;

    public static function initKernel()
    {
        $baseDir = dirname(__FILE__).'/..';
        self::$container = new \Bonefish\DependencyInjection\Container();
        /** @var \Bonefish\Core\Kernel $kernel */
        self::$container->create('\Bonefish\Core\Kernel',array($baseDir));
    }

    public static function postPackageInstall(Event $event)
    {
        self::initKernel();
        /** @var \Bonefish\Core\PackageManager $packageManager */
        $packageManager = self::$container->create('\Bonefish\Core\PackageManager');
        $packageManager->postPackageInstall($event);
    }

    public static function postPackageUninstall(Event $event)
    {
        self::initKernel();
        /** @var \Bonefish\Core\PackageManager $packageManager */
        $packageManager = self::$container->create('\Bonefish\Core\PackageManager');
        $packageManager->postPackageUninstall($event);
    }
} 