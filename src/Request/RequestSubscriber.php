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

namespace Bonefish\Request;


use AValnar\EventDispatcher\EventDispatcher;
use AValnar\EventDispatcher\EventSubscriber;
use AValnar\FileToClassMapper\Mapper;
use Bonefish\Events;
use Bonefish\Injection\Container\Container;
use Bonefish\Reflection\ReflectionService;
use Bonefish\Request\Event\RequestEvent;
use Bonefish\Request\Event\ResponseEvent;
use Bonefish\RouteCollectors\RestRouteCollector;
use Bonefish\Router\Collectors\CombinedRouteCollector;
use Bonefish\Router\Collectors\RouteCollector;
use Bonefish\Router\Request\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestSubscriber implements EventSubscriber
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var Container
     */
    private $container;

    public function __construct(EventDispatcher $eventDispatcher, Container $container)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getEvents() : array
    {
        return [
            'initRequest' => [[Events::REQUEST_INIT]],
            'initCollectors' => [[Events::REQUEST_INIT]],
            'handleRequest' => [[Events::REQUEST_BEFORE_HANDLE, Events::COLLECTORS_INIT], EventDispatcher::USE_LAST],
            'sendResponse' => [[Events::RESPONSE_BEFORE_SEND], EventDispatcher::USE_LAST]
        ];
    }

    public function initRequest()
    {
        $request = Request::createFromGlobals();

        $event = new RequestEvent($request);
        $this->eventDispatcher->dispatch(Events::REQUEST_BEFORE_HANDLE, $event);
    }

    public function initCollectors()
    {
        $routeCollector = $this->container->get(RouteCollector::class);

        if ($routeCollector instanceof CombinedRouteCollector) {
            $routeCollector->addCollector(
                new RestRouteCollector(
                    $this->container->get(Mapper::class),
                    $this->container->get(ReflectionService::class),
                    $this->container,
                    BONEFISH_PACKAGE_PATH,
                    BONEFISH_VENDOR_PATH
                )
            );
        }

        $this->eventDispatcher->dispatch(Events::COLLECTORS_INIT);
    }

    public function handleRequest(array $events)
    {
        /** @var Request $request */
        $request = $events[Events::REQUEST_BEFORE_HANDLE]->getRequest();
        /** @var RequestHandlerInterface $requestHandler */
        $requestHandler = $this->container->get(RequestHandlerInterface::class);

        $response = $requestHandler->handleRequest($request);

        if (!$response instanceof Response) {
            $response = new Response($response);
        }

        $event = new ResponseEvent($request, $response);
        $this->eventDispatcher->dispatch(Events::RESPONSE_BEFORE_SEND, $event);
    }

    public function sendResponse(array $events)
    {
        /** @var Response $response */
        $response = $events[Events::RESPONSE_BEFORE_SEND]->getResponse();
        /** @var Request $request */
        $request = $events[Events::RESPONSE_BEFORE_SEND]->getRequest();

        $response->headers->set('x-bonefish', true);
        $response->prepare($request);
        $response->sendHeaders();
        $response->sendContent();

        $this->eventDispatcher->dispatch(Events::RESPONSE_AFTER_SEND, $events[Events::RESPONSE_BEFORE_SEND]);
    }

}