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
 * @date       2014-08-27
 * @package Bonefish
 */

$baseDir = dirname(__FILE__);
$composerAutoload = $baseDir . '/vendor/autoload.php';

if (!file_exists($composerAutoload))
    die('Please run Composer install first!');

require $baseDir . '/vendor/autoload.php';
session_start();
$container = new Bonefish\DependencyInjection\Container();
/** @var \Bonefish\Core\Kernel $kernel */
$kernel = $container->create('\Bonefish\Core\Kernel',array($baseDir));
$kernel->startTracy();
$kernel->start();


