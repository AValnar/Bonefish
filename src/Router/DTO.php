<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 28.09.2014
 * Time: 23:29
 */

namespace Bonefish\Router;


class DTO
{
    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string
     */
    protected $package;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var array
     */
    protected $parameter = array();

    /**
     * @param string $vendor
     * @param string $package
     * @param string $controller
     * @param string $action
     * @param array $parameter
     */
    public function __construct($vendor, $package, $controller, $action, $parameter = array())
    {
        $this->vendor = $vendor;
        $this->package = $package;
        $this->controller = $controller;
        $this->action = $action;
        $this->parameter = $parameter;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     * @return self
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param string $package
     * @return self
     */
    public function setPackage($package)
    {
        $this->package = $package;
        return $this;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     * @return self
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param array $parameter
     * @return self
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }
} 