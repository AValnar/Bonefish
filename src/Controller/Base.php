<?php

namespace Bonefish\Controller;
use Bonefish\Utility\Environment;
use Bonefish\DI\IContainer;

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
 * @date       2014-08-28
 * @package Bonefish\Controller
 */
abstract class Base
{
    /**
     * @var IContainer
     * @Bonefish\Inject
     */
    public $container;

    /**
     * @var Environment
     * @Bonefish\Inject
     */
    public $environment;

    public function getConfiguration()
    {
        return $this->environment->getPackage()->getConfiguration();
    }

    public function indexAction()
    {

    }

    /**
     * @param string $route
     */
    protected function redirect($route)
    {
        $route = $this->container->create($route);
        $dto = $route->getDTO();
        /** @var \Bonefish\Router\FastRoute $router */
        $router = $this->container->get('\Bonefish\Router\FastRoute');
        $router->callControllerDTO($dto);
    }
} 