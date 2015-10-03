<?php
/**
 * Copyright (C) 2015  Alexander Schmidt
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
 * @copyright  Copyright (c) 2015, Alexander Schmidt
 * @date       03.10.2015
 */

$basePath = dirname(__FILE__);

require_once $basePath . '/vendor/autoload.php';

// Create EventDispatcher
$eventDispatcher = new \AValnar\EventDispatcher\EventDispatcherImpl();
/** @var \Bonefish\Injection\Container\Container $container */
$container = null;

// Create Event to trigger ready state
$ready = function($events = []) use (&$container) {
    /** @var \AValnar\EventStrap\Event\ObjectCreatedEvent $containerEvent */
    $containerEvent = array_pop($events);
    $container = $containerEvent->getObject();
};
$eventDispatcher->addListener($ready, 2, 'bonefish.containerCreated');

// Bootstrap app
$eventStrap = new \AValnar\EventStrap\EventStrap($eventDispatcher);

$decoder = new \Nette\Neon\Neon();
$configurator = new \AValnar\EventStrap\Configurator\NeonConfigurator($decoder);
$configurator->setConfiguration($basePath . '/configuration/bootstrap.neon');

$eventStrap->configure($configurator->getConfiguration());
$eventStrap->bootstrap();

// Setup Container
$interfaceResolver = new \Bonefish\Injection\Resolver\InterfaceResolver();
$interfaceResolver->addImplementation(
    \Bonefish\Reflection\ClassNameResolverInterface::class,
    \Bonefish\Reflection\ClassNameResolver::class
);
$interfaceResolver->addImplementation(
    \Bonefish\Router\Request\RequestHandlerInterface::class,
    \Bonefish\Router\Request\FastRouteRequestHandler::class
);
$interfaceResolver->addImplementation(
    \Bonefish\Router\Collectors\RouteCollector::class,
    \Bonefish\Router\Collectors\CombinedRouteCollector::class
);
$container->addResolver($interfaceResolver);

$classNameResolver = $container->get(\Bonefish\Injection\Resolver\ClassNameResolver::class);

/** @var \Bonefish\Router\Request\RequestHandlerInterface $requestHandler */
$requestHandler = $container->get(\Bonefish\Router\Request\RequestHandlerInterface::class);
$request = new \Bonefish\Router\Request\Request('GET', '/');

$requestHandler->handleRequest($request);