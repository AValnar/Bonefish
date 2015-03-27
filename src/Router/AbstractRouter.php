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
 * @date       2014-09-29
 * @package Bonefish\Router
 */
abstract class AbstractRouter
{
    /**
     * @var array
     */
    protected $parameter = array();

    /**
     * @var \League\Url\AbstractUrl
     */
    protected $url;

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    const VALID_TYPES = array('get', 'post', 'put', 'delete', 'head');

    const DEFAULT_TYPE = 'GET';

    /**
     * @param \League\Url\UrlImmutable $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $action
     * @param mixed $controller
     */
    protected function callControllerAction($action, $controller)
    {
        if (is_callable(array($controller, $action))) {
            $this->sortParameters($controller, $action);
            call_user_func_array(array($controller, $action), $this->parameter);
        } else {
            $controller->indexAction();
        }
    }

    /**
     * @param mixed $controller
     * @param string $action
     */
    protected function sortParameters($controller, $action)
    {
        $r = \Nette\Reflection\Method::from($controller, $action);
        $userParams = array();
        $methodParams = $r->getParameters();
        foreach ($methodParams as $key => $parameter) {
            if (isset($this->parameter[$parameter->getName()])) {
                $userParams[$key] = $this->parameter[$parameter->getName()];
            }
        }
        $this->parameter = $userParams;
    }

    public static function validateType($type)
    {
        if (!in_array(strtolower($type), AbstractRouter::VALID_TYPES)) {
            $type = AbstractRouter::DEFAULT_TYPE;
        }

        return strtoupper($type);
    }
} 