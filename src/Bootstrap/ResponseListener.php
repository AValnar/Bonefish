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
use AValnar\EventStrap\Event\ObjectCreatedEvent;
use AValnar\EventStrap\Listener\AbstractEventStrapListener;
use Bonefish\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResponseListener extends AbstractEventStrapListener
{


    /**
     * @var string
     */
    private $beforeHandleEvent;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $this->beforeHandleEvent = $options['beforeEvent'];
    }

    /**
     * @param Event[] $events
     */
    public function onEventFired(array $events = [])
    {
        /** @var RequestEvent $event */
        $event = array_pop($events);

        /** @var Response $response */
        $request = $event->getRequest();

        /** @var Response $response */
        $response = $event->getResponse();

        $this->eventDispatcher->dispatch($this->beforeHandleEvent, $event);

        $response->headers->set('x-bonefish', true);
        $response->prepare($request);
        $response->send();

        $this->eventDispatcher->dispatch($this->event, new RequestEvent($request, $response));
    }
}