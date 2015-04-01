<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 31.03.2015
 * Time: 21:28
 */
namespace Bonefish\DI;

interface IContainer
{
    /**
     * Add an object into the container
     *
     * @param string $className
     * @param mixed $obj
     * @throws \Exception
     */
    public function add($className, $obj);

    /**
     * Set an implementation to use for an interface
     *
     * @param string $implementation
     * @param string $interface
     */
    public function setInterfaceImplementation($implementation, $interface);

    /**
     * Get an implementation to use for an interface or NULL if not set
     *
     * @param string $interface
     * @return string|NULL
     */
    public function getInterfaceImplementation($interface);

    /**
     * Get a singleton and create if needed
     *
     * @param string $className
     * @return mixed
     */
    public function get($className);

    /**
     * Create a object with dependency injection via annotation
     *
     * @param string $className
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function create($className, $parameters = array());

    /**
     * Perform lazy dependency injection on object and init object
     *
     * @param mixed $obj
     * @param bool $init
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function finalizeObject($obj, $init = false, $parameters = array());

    /**
     * Clear all services
     */
    public function tearDown();

    /**
     * Return array of all created services
     *
     * @return array
     */
    public function getSingletons();

    /**
     * Check if i singleton was already created
     *
     * @param string $className
     * @return bool
     */
    public function exists($className);

}