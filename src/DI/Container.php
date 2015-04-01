<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 31.03.2015
 * Time: 21:23
 */

namespace Bonefish\DI;


use Bonefish\DependencyInjection\Container as BaseContainer;
use Bonefish\DependencyInjection\Proxy;
use Nette\Reflection\AnnotationsParser;
use Nette\Reflection\ClassType;

class Container extends BaseContainer implements IContainer
{
    /**
     * @var ClassType
     */
    protected $currentReflection = NULL;

    /**
     * @var array
     */
    protected $reflections = array();

    const FACTORY_NS_SUFFIX = 'Factory';

    /**
     * @param string $className
     * @return bool
     */
    protected function injectSelf($className)
    {
        return (ltrim($className, '\\') == Container::class);
    }

    /**
     * Add an object into the container
     *
     * @param string $className
     * @param mixed $obj
     * @throws \Exception
     */
    public function add($className, $obj)
    {
        if ($this->injectSelf($className)) {
            throw new \InvalidArgumentException('You can not add the Container!');
        }

        if (isset($this->objects[$className])) {
            throw new \InvalidArgumentException('Duplicate entry for key ' . $className);
        }

        $this->objects[$className] = $obj;
    }

    /**
     * Get a singleton and create if needed
     *
     * @param string $className
     * @return mixed
     */
    public function get($className)
    {
        $className = $this->resolveClassName($className);

        if ($this->injectSelf($className)) {
            return $this;
        }

        if (!isset($this->objects[$className])) {
            $this->objects[$className] = $this->create($className);
        }

        return $this->objects[$className];
    }

    /**
     * Create a object with dependency injection via annotation
     *
     * @param string $className
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */

    public function create($className, $parameters = array())
    {
        $className = $this->resolveClassName($className);

        if ($this->injectSelf($className)) {
            return $this;
        }

        $factoryClassName = $this->getFactoryClassName($className);
        if (class_exists($factoryClassName)) {
            $factory = $this->get($factoryClassName);
            return $factory->create($parameters);
        }

        return $this->finalizeObject($className, TRUE, $parameters);
    }

    protected function getFactoryClassName($className)
    {
        $parts = explode('\\', $className);
        $class = array_pop($parts);

        $factoryName = $class . self::FACTORY_NS_SUFFIX;
        return implode('\\', $parts) .'\\'.self::FACTORY_NS_SUFFIX.'\\'. $factoryName;
    }

    /**
     * Set an implementation to use for an interface
     *
     * @param string $implementation
     * @param string $interface
     */
    public function setInterfaceImplementation($implementation, $interface)
    {
        $this->alias($implementation, $interface);
    }

    /**
     * Get an implementation to use for an interface or NULL if not set
     *
     * @param string $interface
     * @return string|NULL
     */
    public function getInterfaceImplementation($interface)
    {
        $implementation = $this->getAliasForClass($interface);
        return $implementation !== $interface ? $implementation : NULL;
    }

    /**
     * Perform lazy Dependency Injection
     *
     * @param mixed $parent
     * @param \ReflectionProperty $property
     * @param string $className
     * @param bool $eager
     * @param \Nette\Reflection\ClassType $r
     */

    protected function performDependencyInjection($parent, \ReflectionProperty $property, $className, $eager, $r)
    {
        if ($property->getDeclaringClass()->getName() !== $r->getName()) {
            $r = $this->getReflection($property->getDeclaringClass()->getName());
        }

        $className = $this->resolveClassName($className, $r);

        if ($this->injectSelf($className)) {
            $value = $this;
        } else {
            if (!$eager && !isset($this->objects[$className])) {
                $value = new Proxy($className, $property, $parent, $this);
            } else {
                $value = $this->get($className);
            }
        }
        $this->injectValueIntoProperty($parent, $property, $value);
    }

    /**
     * @param string $className
     * @param ClassType $foundInClassReflection
     * @return string
     */
    protected function resolveClassName($className, $foundInClassReflection = NULL)
    {
        if ($foundInClassReflection === NULL) {
            $foundInClassReflection = $this->getReflection($className);
        }

        $className = '\\' . AnnotationsParser::expandClassName($className, $foundInClassReflection);

        $r = $this->getReflection($className);

        if ($r->isInterface()) {
            $implementation = $this->getInterfaceImplementation($className);
            if ($implementation === NULL) {
                throw new \RuntimeException('Tried to inject interface without setting an implementation!');
            }
            $className = $implementation;
        }

        return $className;
    }

    /**
     * @param string $className
     * @return ClassType
     */
    protected function getReflection($className)
    {
        if (!isset($this->reflections[$className])) {
            $this->reflections[$className] = new ClassType($className);
        }

        return $this->reflections[$className];
    }
}