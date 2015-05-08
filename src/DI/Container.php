<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 31.03.2015
 * Time: 21:23
 */

namespace Bonefish\DI;


use Bonefish\DependencyInjection\Container as BaseContainer;
use Nette\Reflection\AnnotationsParser;
use Nette\Reflection\ClassType;
use Nette\Reflection\Property;

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

    protected static $defaultInjectAnnotations = array('Bonefish\Inject', 'inject');

    /**
     * @param string $className
     * @return bool
     */
    protected function injectSelf($className)
    {
        return (ltrim($className, '\\') == get_class($this));
    }

    /**
     * @param string $className
     * @return bool
     */
    public function exists($className)
    {
        return isset($this->objects[$className]['']);
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

        if (isset($this->objects[$className][''])) {
            throw new \InvalidArgumentException('Duplicate entry for key ' . $className);
        }

        $this->objects[$className][''] = $obj;
    }

    /**
     * Get a singleton and create if needed
     *
     * @param string $className
     * @param array $parameters
     * @return mixed
     */
    public function get($className, $parameters = array())
    {
        $className = $this->resolveClassName($className);

        $implodedParameters = implode(',', $parameters);

        if ($this->injectSelf($className)) {
            return $this;
        }

        if (!isset($this->objects[$className][$implodedParameters])) {
            $this->objects[$className][$implodedParameters] = $this->create($className, $parameters);
        }

        return $this->objects[$className][$implodedParameters];
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
            $obj = $factory->create($parameters);
            return $this->finalizeObject($obj);
        }

        return $this->finalizeObject($className, TRUE, $parameters);
    }

    protected function getFactoryClassName($className)
    {
        $parts = explode('\\', $className);
        $class = array_pop($parts);

        $factoryName = $class . self::FACTORY_NS_SUFFIX;
        return implode('\\', $parts) . '\\' . self::FACTORY_NS_SUFFIX . '\\' . $factoryName;
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
     * @param object $obj
     * @param Property $property
     * @param \Nette\Reflection\ClassType $r
     * @throws \Exception
     */
    protected function processProperty($obj, Property $property, $r)
    {
        foreach(self::$defaultInjectAnnotations as $annotation) {
            if ($property->hasAnnotation($annotation)) {
                if (!$property->hasAnnotation('var')) {
                    throw new \Exception('No @var tag found for property ' . $property->getName() . ' with @' . self::INJECT_ANNOTATION . ' tag');
                }
                $class = $property->getAnnotation('var');
                $parameters = $this->getInjectParameters($property->getAnnotation($annotation));
                $eager = in_array('eagerly', $parameters);
                $this->performDependencyInjection($obj, $property, $class, $eager, $r, $parameters);
                break;
            }
        }
    }

    protected function getInjectParameters($parameterValue)
    {
        $parameters = array();
        if (is_string($parameterValue) || is_int($parameterValue)) {
            $parameters[] = $parameterValue;
        } elseif(is_object($parameterValue) && $parameterValue instanceof \ArrayAccess) {
            foreach($parameterValue as $key => $val) {
                $parameters[$key] = $val;
            }
        }

        return $parameters;
    }

    /**
     * Perform lazy Dependency Injection
     *
     * @param mixed $parent
     * @param Property $property
     * @param string $className
     * @param bool $eager
     * @param array $parameters
     * @param \Nette\Reflection\ClassType $r
     */
    protected function performDependencyInjection($parent, Property $property, $className, $eager, $r, $parameters)
    {
        if ($property->getDeclaringClass()->getName() !== $r->getName()) {
            $r = $this->getReflection($property->getDeclaringClass()->getName());
        }

        $className = $this->resolveClassName($className, $r);

        if ($this->injectSelf($className)) {
            $value = $this;
        } else {
            if (!$eager && !isset($this->objects[$className][implode(',', $parameters)])) {
                $value = new Proxy($className, $property, $parent, $this, $parameters);
            } else {
                $value = $this->get($className, $parameters);
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