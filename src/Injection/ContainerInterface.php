<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 14.05.2015
 * Time: 15:07
 */

namespace Bonefish\Injection;

use Bonefish\Injection\Exceptions\RuntimeException;
use Bonefish\Reflection\Meta\ClassMeta;

interface ContainerInterface
{
    const INJECT_ANNOTATIONS = array('inject', 'Bonefish\Inject');

    const FACTORY_NAMESPACE = 'Factory';

    const FACTORY_SUFFIX = 'Factory';

    /**
     * Fetch a service with supplied parameters.
     * This method will call create for a new service and store it for later use.
     *
     * @param $className
     * @param array $parameters
     * @return object
     */
    public function get($className, array $parameters = array());

    /**
     * Create a class with supplied parameters.
     *
     * Before a class is created the container will first check if the requested class is an interface and will try to
     * find an registered implementation. If no implementation was found a RuntimeException is thrown.
     *
     * After the implementation has been resolved and before the object is created the container will check if a factory
     * for this class exists. This Factory has to implement the interface \Bonefish\Factory\IFactory.
     * The name is resolved as follows \Full\Name\Space\Class => \Full\Name\Space\FACTORY_NAMESPACE\ClassFACTORY_SUFFIX
     * E.g. \Bonefish\Core\Environment => \Bonefish\Core\Factory\EnvironmentFactory
     * When a factory exists get() will be called to retrieve an instance and will be used to create an instance.
     *
     * After the class has been created performInjections() is called on the object.
     * After performInjections() was called the method __init() will be called on the object
     * if the method exists.
     *
     * @param $className
     * @param array $parameters
     * @return object
     * @throws RuntimeException
     */
    public function create($className, array $parameters = array());

    /**
     * Set a class to be used for a specific interface
     *
     * @param string $interface
     * @param string $implementation The fully qualified class name of the implementation
     */
    public function setInterfaceImplementation($interface, $implementation);

    /**
     * Perform dependency injections for matching properties of $object.
     *
     * This function will use all properties with an matching inject annotation and inject the class
     * annotated with var via get().
     *
     * You can define injection parameters after the inject annotation as either a string or array.
     * E.g. @Bonefish\Inject foo
     * @Bonefish\Inject(vector=XYZ, key=ABC)
     *
     * All injection have to be lazy if the requested service is not yet created.
     * This means a proxy will instead be inserted which will replace itself.
     *
     * @param object $object
     * @param ClassMeta $classMeta
     */
    public function performInjections($object, ClassMeta $classMeta = null);
}