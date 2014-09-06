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

Tracy\Debugger::enable(Tracy\Debugger::DEVELOPMENT);
Tracy\Debugger::$strictMode = TRUE;

$container = new Bonefish\DependencyInjection\Container();

$autoloader = new Bonefish\Autoloader\Autoloader();
$autoloader->register();
$container->add('\Bonefish\Autoloader\Autoloader', $autoloader);

/** @var \Bonefish\Core\Environment $environment */
$environment = $container->get('\Bonefish\Core\Environment')
    ->setBasePath($baseDir)
    ->setModulePath('/modules')
    ->setConfigurationPath('/configuration')
    ->setCachePath('/cache');

$latte = $container->get('Latte\Engine');
$latte->setTempDirectory($environment->getFullCachePath().'/latte');

try {
    $dbConfig = $container->get('\Bonefish\Core\ConfigurationManager')->getConfiguration('db.ini');
    $dbh = new PDO($dbConfig->db_dsn, $dbConfig->db_user, $dbConfig->db_pw);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$mapper = new \Respect\Relational\Mapper($dbh);
$container->add('\Respect\Relational\Mapper',$mapper);

$url = League\Url\UrlImmutable::createFromServer($_SERVER);
$router = $container->create('Bonefish\Router\Router', array($url));
$router->route();


