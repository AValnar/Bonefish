<?php
/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-08-27
 * @package    Bonefish
 */

$baseDir = dirname(__FILE__);
$composerAutoload = $baseDir . '/vendor/autoload.php';

if (!file_exists($composerAutoload))
    die('Please run Composer install first!');

require $baseDir . '/vendor/autoload.php';

Tracy\Debugger::enable(Tracy\Debugger::DEVELOPMENT);
Tracy\Debugger::$strictMode = TRUE;

$autoloader = new Bonefish\Autoloader\Autoloader();
$autoloader->register();

$container = new Bonefish\DependencyInjection\Container();

$url = League\Url\UrlImmutable::createFromServer($_SERVER);
$routeConfig = new Respect\Config\Container($baseDir.'/configuration/route.ini');
$router = $container->create('Bonefish\Router\Router',array($url,$baseDir,$autoloader,$routeConfig));
$router->route();


