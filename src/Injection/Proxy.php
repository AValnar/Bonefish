<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 01.04.2015
 * Time: 21:49
 */

namespace Bonefish\Injection;


class Proxy
{
    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $property;

    /**
     * @var object
     */
    protected $parent;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param string $className
     * @param string $property
     * @param object $parent
     * @param array $parameters
     * @param ContainerInterface $container
     */
    public function __construct($className, $property, $parent, $container, array $parameters = array())
    {
        $this->className = $className;
        $this->property = $property;
        $this->parent = $parent;
        $this->container = $container;
        $this->parameters = $parameters;
    }

    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments = array())
    {
        $dependency = $this->container->get($this->className, $this->parameters);
        $this->parent->{$this->property} = $dependency;

        return call_user_func_array(array($this->parent->{$this->property}, $name), $arguments);
    }


    public function __sleep()
    {
        // Break the proxy, because objects with proxies in them should most likely not be serialised anyway
        return array('className');
    }
}