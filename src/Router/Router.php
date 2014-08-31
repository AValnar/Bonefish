<?php
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
 * @date       2014-08-31
 * @package Bonefish\Router
 */

namespace Bonefish\Router;


class Router
{

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
     * @var \Bonefish\Autoloader\Autoloader
     * @inject
     */
    public $autoloader;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var bool|string
     */
    protected $vendor = FALSE;

    /**
     * @var bool|string
     */
    protected $package = FALSE;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $parameter = array();

    /**
     * @var \Respect\Config\Container
     * @property string $vendor
     * @property string $package
     */
    protected $config;

    /**
     * @param \League\Url\UrlImmutable $url
     * @param \Respect\Config\Container $config
     */
    public function __construct($url,$config)
    {
        $this->url = $url;
        $this->config = $config;
    }

    public function route()
    {
        try {
            $this->resolveRoute();
            $package = $this->$environment->createPackage($this->vendor,$this->package);
            $controller = $package->getController(\Bonefish\Core\Package::TYPE_CONTROLLER);
        } catch (\Exception $e) {
            die('No Route found!');
        }

        $action = $this->action.'Action';
        $this->callControllerAction($action,$controller);
    }

    /**
     * @param string $action
     * @param mixed $controller
     */
    protected function callControllerAction($action,$controller)
    {
        if (is_callable(array($controller,$action))) {
            $this->sortParameters($controller,$action);
            call_user_func_array(array($controller,$action),$this->parameter);
        } else {
            $controller->indexAction();
        }
    }

    /**
     * @return string
     */
    protected function resolveRoute()
    {
        $path = urldecode($this->url->getPath());
        $parts = explode('/',$path);

        foreach($parts as $part) {
            $this->setVendorPackageAndActionFromUrl($part);
        }

        $this->setDefault('vendor');
        $this->setDefault('package');
    }

    protected function setVendorPackageAndActionFromUrl($part)
    {
        $ex = explode(':',$part,2);
        if (!isset($ex[1])) {
            return;
        }
        switch($ex[0]) {
            case 'v':
                $this->vendor = $ex[1];
                break;
            case 'p':
                $this->package = $ex[1];
                break;
            case 'a':
                $this->action = $ex[1];
                break;
            default:
                $this->parameter[$ex[0]] = $ex[1];
                break;
        }
    }

    /**
     * @param string $value
     */
    protected function setDefault($value)
    {
        if (!$this->{$value}) {
            $this->{$value} = $this->config->{$value};
        }
    }

    /**
     * @param mixed $controller
     * @param string $action
     */
    protected function sortParameters($controller,$action)
    {
        $r = \Nette\Reflection\Method::from($controller,$action);
        $userParams = array();
        $methodParams = $r->getParameters();
        foreach($methodParams as $key => $parameter) {
            if (isset($this->parameter[$parameter->getName()])) {
                $userParams[$key] = $this->parameter[$parameter->getName()];
            }
        }
        $this->parameter = $userParams;
    }
} 