<?php

namespace Bonefish\Router;
use Bonefish\DI\IContainer;
use League\Url\AbstractUrl;
use Bonefish\Utility\Environment;
use Bonefish\Utility\ConfigurationManager;

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
    protected $parameter = [];

    /**
     * @var \League\Url\AbstractUrl
     */
    protected $url = null;

    /**
     * @var Environment
     * @Bonefish\Inject
     */
    public $environment;

    /**
     * @var IContainer
     * @Bonefish\Inject
     */
    public $container;

    /**
     * @var ConfigurationManager
     * @Bonefish\Inject
     */
    public $configurationManager;

    public static $validTypes = ['get', 'post', 'put', 'delete', 'head'];

    const DEFAULT_TYPE = 'GET';

    abstract public function route();

    /**
     * @return AbstractUrl
     */
    public function getUrl()
    {
        if ($this->url === null)
        {
            $server = $_SERVER;
            $url = UrlImmutable::createFromServer($server);
            $this->setUrl($url);
        }

        return $this->url;
    }

    /**
     * @param AbstractUrl $url
     */
    public function setUrl(AbstractUrl $url)
    {
        $this->url = $url;
    }

    /**
     * @param string $action
     * @param mixed $controller
     */
    protected function callControllerAction($action, $controller)
    {
        $callable = [$controller, $action];
        if (is_callable($callable)) {
            $this->sortParameters($controller, $action);
            call_user_func_array($callable, $this->parameter);
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
        $userParams = [];
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
        if (!in_array(strtolower($type), AbstractRouter::$validTypes)) {
            $type = AbstractRouter::DEFAULT_TYPE;
        }

        return strtoupper($type);
    }
} 