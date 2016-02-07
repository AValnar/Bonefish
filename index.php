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
 * @copyright  Copyright (c) 2015, Alexander Schmidt
 * @date       03.10.2015
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configuration/env.php';

// Create EventDispatcher
$eventDispatcher = new \AValnar\EventDispatcher\EventDispatcherImpl();
$eventDispatcher->addSubscriber(new \Bonefish\Bootstrap\BootSubscriber($eventDispatcher));

$eventDispatcher->addListener(
    function ($events) use ($eventDispatcher) {
        /** @var \Bonefish\Injection\Container\Container $container */
        $container = $events[\Bonefish\Events::CONTAINER_SETUP]->getObject();
        $eventDispatcher->addSubscriber($container->get(\Bonefish\Request\RequestSubscriber::class));
        $eventDispatcher->dispatch(\Bonefish\Events::REQUEST_INIT);
    },
    \AValnar\EventDispatcher\EventDispatcher::USE_LAST,
    \Bonefish\Events::CONTAINER_SETUP
);

$eventDispatcher->dispatch(\Bonefish\Events::BOOT_INIT);