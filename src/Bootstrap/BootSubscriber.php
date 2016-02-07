<?php
declare(strict_types = 1);
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
 * @copyright  Copyright (c) 2016, Alexander Schmidt
 * @date       07.02.16
 */

namespace Bonefish\Bootstrap;


use AValnar\Doctrine\Factory\AnnotationReaderFactory;
use AValnar\EventDispatcher\EventDispatcher;
use AValnar\EventDispatcher\EventSubscriber;
use Bonefish\Bootstrap\Event\ObjectEvent;
use Bonefish\Events;
use Bonefish\Injection\Container\Container;
use Bonefish\Injection\Resolver\ClassNameResolver;
use Bonefish\Injection\Resolver\InterfaceResolver;
use Bonefish\Reflection\ReflectionService;
use Bonefish\Utility\Configuration\NeonConfiguration;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\Cache;
use Tracy\Debugger;

final class BootSubscriber implements EventSubscriber
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array
     */
    public function getEvents() : array
    {
        return [
            'initTracy' => [[Events::BOOT_INIT]],
            'initCache' => [[Events::BOOT_INIT]],
            'initAnnotationReader' => [[Events::CACHE_INIT]],
            'initReflectionService' => [[Events::CACHE_INIT, Events::ANNOTATION_READER_INIT]],
            'initContainer' => [[Events::REFLECTION_SERVICE_INIT]],
            'configureContainer' => [[Events::CONTAINER_INIT]],
        ];
    }

    public function initTracy()
    {
        Debugger::enable(!BONEFISH_DEV_MODE, BONEFISH_LOG_PATH, BONEFISH_ADMIN_MAIL);
    }

    public function initCache()
    {
        $cacheType = BONEFISH_CACHE_TYPE;
        /** @var Cache $cache */
        $cache = new $cacheType();

        $this->emitObject(Events::CACHE_INIT, $cache);
    }

    /**
     * @param array $events
     */
    public function initAnnotationReader(array $events)
    {
        /** @var Cache $cache */
        $cache = $this->getObject($events, Events::CACHE_INIT);
        $factory = new AnnotationReaderFactory();
        $annotationReader = $factory->create([
            'cache' => $cache,
            'debug' => BONEFISH_DEV_MODE,
            'indexed' => false
        ]);

        $this->emitObject(Events::ANNOTATION_READER_INIT, $annotationReader);
    }

    /**
     * @param array $events
     */
    public function initReflectionService(array $events)
    {
        /** @var Cache $cache */
        $cache = $this->getObject($events, Events::CACHE_INIT);
        /** @var AnnotationReader $annotationReader */
        $annotationReader = $this->getObject($events, Events::ANNOTATION_READER_INIT);
        $reflectionService = new ReflectionService($cache, $annotationReader);

        $this->emitObject(Events::REFLECTION_SERVICE_INIT, $reflectionService);
    }

    /**
     * @param array $events
     */
    public function initContainer(array $events)
    {
        /** @var ReflectionService $reflectionService */
        $reflectionService = $this->getObject($events, Events::REFLECTION_SERVICE_INIT);

        $container = new Container($reflectionService);

        $this->emitObject(Events::CONTAINER_INIT, $container);
    }

    /**
     * @param array $events
     */
    public function configureContainer(array $events)
    {
        /** @var Container $container */
        $container = $this->getObject($events, Events::CONTAINER_INIT);

        $configuration = $container->create(NeonConfiguration::class)
            ->getConfiguration(BONEFISH_CONFIG_PATH . '/interfaces.neon');

        $interfaceResolver = new InterfaceResolver();
        foreach ($configuration as $interface => $implementation) {
            $interfaceResolver->addImplementation($interface, $implementation);
        }

        $container->addResolver($interfaceResolver);
        $container->addResolver($container->get(ClassNameResolver::class), 10);
        $container->add($this->eventDispatcher);

        $this->emitObject(Events::CONTAINER_SETUP, $container);
    }

    /** Helper methods */

    /**
     * @param string $eventName
     * @param $object
     */
    private function emitObject(string $eventName, $object)
    {
        $event = new ObjectEvent($object);
        $this->eventDispatcher->dispatch($eventName, $event);
    }

    /**
     * @param array $events
     * @param string $eventName
     * @return object
     */
    private function getObject(array $events, string $eventName)
    {
        return $events[$eventName][0]->getObject();
    }
}