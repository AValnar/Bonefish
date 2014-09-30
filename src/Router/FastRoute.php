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
        $routes = $this->environment->getFullCachePath() . '/route.cache';

        if (!file_exists($routes)) {
            throw new \InvalidArgumentException('No routes found please use Bonefish Core generateRoutes');
        }

        $dispatcher = \FastRoute\cachedDispatcher(function (){}, [
            'cacheFile' => $routes
        ]);

        $data = $dispatcher->dispatch('GET', '/' . urldecode($this->url->getPath()));
        switch ($data[0]) {
            case \FastRoute\Dispatcher::FOUND:
                /** @var DTO $dto */
                $dto = unserialize($data[1]);
                $package = $this->environment->createPackage($dto->getVendor(), $dto->getPackage());
                $this->environment->setPackage($package);
                $controller = $this->container->get($dto->getController());
                $this->parameter = $data[2];
                $this->callControllerAction($dto->getAction().'Action',$controller);
                break;
            default:
                throw new \Exception('No route found!');
                break;
        }
    }
} 