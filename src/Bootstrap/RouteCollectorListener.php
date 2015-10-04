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
 * @date       04.10.2015
 */

namespace Bonefish\Bootstrap;


use AValnar\EventDispatcher\Event;
use AValnar\EventStrap\Listener\AbstractEventStrapListener;
use AValnar\FileToClassMapper\Mapper;
use Bonefish\Injection\Container\ContainerInterface;
use Bonefish\Reflection\ReflectionService;
use Bonefish\Router\Collectors\CombinedRouteCollector;
use Bonefish\Router\Collectors\RouteCollector;

final class RouteCollectorListener extends AbstractEventStrapListener
{

    /**
     * @var string[]
     */
    private $collectors = [];

    /**
     * @var string
     */
    private $vendorDir;

    /**
     * @var string
     */
    private $packageDir;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $this->collectors = $options['collectors'];
        $this->vendorDir = $options['vendorDir'];
        $this->packageDir = $options['packageDir'];
    }

    /**
     * @param Event[] $events
     */
    public function onEventFired(array $events = [])
    {
        /** @var ContainerInterface $container */
        $container = $events['bonefish.container.setup']->getObject();

        /** @var CombinedRouteCollector $routeCollector */
        $routeCollector = $container->get(RouteCollector::class);

        foreach($this->collectors as $collector)
        {
            $object = new $collector(
                $container->get(Mapper::class),
                $container->get(ReflectionService::class),
                $container,
                $this->packageDir,
                $this->vendorDir
            );

            $routeCollector->addCollector($object);
        }

        $this->emit($container);
    }
}