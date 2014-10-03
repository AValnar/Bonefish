<?php

namespace Bonefish\Router;

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
 * @date       2014-09-28
 * @package Bonefish\Router
 */
class FastRoute extends AbstractRouter
{

    /**
     * @throws \Exception
     */
    public function route()
    {
        if ($this->url->getPath() == '') {
            $dto = $this->getRouteDTO('\Bonefish\Router\Routes\DefaultRoute');
        } else {
            $dto = $this->dispatch();
        }

        $this->callControllerDTO($dto);
    }

    /**
     * @return DTO
     */
    protected function dispatch()
    {
        $dispatcher = $this->createCachedDispatcher();

        $data = $dispatcher->dispatch('GET', '/' . urldecode($this->url->getPath()));

        if ($data[0] === \FastRoute\Dispatcher::FOUND) {
            $this->parameter = $data[2];
            return unserialize($data[1]);
        }

        return $this->getRouteDTO('\Bonefish\Router\Routes\NotFound');
    }

    /**
     * @return \FastRoute\Dispatcher
     */
    protected function createCachedDispatcher()
    {
        $routes = $this->environment->getFullCachePath() . '/route.cache';

        if (!file_exists($routes)) {
            throw new \InvalidArgumentException('No routes found please use Bonefish Core generateRoutes');
        }

        return \FastRoute\cachedDispatcher(function (){}, [
            'cacheFile' => $routes
        ]);
    }

    /**
     * @param DTO $dto
     */
    public function callControllerDTO($dto)
    {
        $package = $this->environment->createPackage($dto->getVendor(), $dto->getPackage());
        $this->environment->setPackage($package);
        $controller = $this->container->get($dto->getController());
        $this->callControllerAction($dto->getAction().'Action',$controller);
    }

    /**
     * @param string $route
     * @return DTO
     */
    protected function getRouteDTO($route)
    {
        $route = $this->container->create($route);
        return $route->getDTO();
    }
} 