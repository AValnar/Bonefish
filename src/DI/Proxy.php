<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 01.04.2015
 * Time: 21:49
 */

namespace Bonefish\DI;


class Proxy extends \Bonefish\DependencyInjection\Proxy
{
    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param string $className
     * @param \Nette\Reflection\Property $property
     * @param mixed $parent
     * @param Container $container
     */
    public function __construct($className, $property, $parent, $container, $parameters)
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
        $this->property->setAccessible(true);
        $this->property->setValue($this->parent, $dependency);

        return call_user_func_array(array($this->parent->{$this->property->getName()}, $name), $arguments);
    }
}