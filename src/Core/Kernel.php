<?php

namespace Bonefish\Core;
use Bonefish\Core\Mode\FullStackMode;

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
 * @date       2014-09-21
 * @package Bonefish\Core
 */
class Kernel
{
    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var FullStackMode
     * @inject
     */
    public $fullStackMode;

    /**
     * @var string
     */
    private $baseDir;

    /**
     * @param string $baseDir
     */
    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function __init()
    {
        $this->fullStackMode->setParameters(array('basePath' => $this->baseDir));
        $this->fullStackMode->init();
    }

    public function start()
    {
        $url = \League\Url\UrlImmutable::createFromServer($_SERVER);
        $router = $this->container->create('\Bonefish\Router\FastRoute', array($url));
        $this->container->add('\Bonefish\Router\FastRoute', $router);
        $router->route();
    }

} 