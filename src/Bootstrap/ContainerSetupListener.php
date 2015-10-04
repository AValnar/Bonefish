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
use Bonefish\Injection\Container\ContainerInterface;
use Bonefish\Injection\Resolver\ClassNameResolver;
use Bonefish\Injection\Resolver\InterfaceResolver;
use Nette\Neon\Neon;

final class ContainerSetupListener extends ContainerListener
{

    /**
     * @var string
     */
    private $interfaceConfiguration;

    /**
     * @var Neon
     */
    private $neon;

    /**
     * @param array $options
     * @param Neon $neon
     */
    public function __construct($options, Neon $neon = null)
    {
        $this->interfaceConfiguration = $options['config'];

        if (!$neon instanceof Neon) {
            $neon = new Neon();
        }

        $this->neon = $neon;
    }

    /**
     * @param Event[] $events
     */
    public function onEventFired(array $events = [])
    {
        $configuration = $this->neon->decode(file_get_contents($this->interfaceConfiguration));

        $container = $this->getContainer($events);

        $this->setupInterfaceResolver($container, $configuration);
        $this->setupClassNameResolver($container);

        $this->emit($container);
    }

    /**
     * @param ContainerInterface $container
     * @param array $configuration
     */
    private function setupInterfaceResolver(ContainerInterface $container, array $configuration)
    {
        $interfaceResolver = new InterfaceResolver();

        foreach($configuration as $interface => $implementation)
        {
            $interfaceResolver->addImplementation($interface, $implementation);
        }

        $container->addResolver($interfaceResolver);
    }

    /**
     * @param ContainerInterface $container
     */
    private function setupClassNameResolver(ContainerInterface $container)
    {
        /** @var ClassNameResolver $classNameResolver */
        $classNameResolver = $container->get(ClassNameResolver::class);
        $container->addResolver($classNameResolver, 10);
    }
}