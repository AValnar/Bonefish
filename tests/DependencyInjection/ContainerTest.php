<?php
/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-07-20
 * @package    Bonefish
 * @subpackage DependencyInjection
 */

namespace Bonefish\Tests\DependencyInjection;


use Bonefish\DependencyInjection\Container;
use Bonefish\DependencyInjection\Proxy;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Container
     */
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testAlias()
    {
        $this->container->alias('foo', 'bar');
        $this->assertEquals(true, $this->container->issetAlias('bar'));
    }

    public function testAdd()
    {
        $this->container->add('foo', new \stdClass());
        $this->container->add('bar', new \stdClass());
        $this->container->add('baz', new \stdClass());
        $this->assertEquals(array('foo', 'bar', 'baz'), $this->container->getSingletons());
    }

    /**
     * @depends testAdd
     */
    public function testTearDown()
    {
        $this->container->add('foo', new \stdClass());
        $this->assertEquals(array('foo'), $this->container->getSingletons());
        $this->container->tearDown();
        $this->assertEquals(array(), $this->container->getSingletons());
    }

    public function testCreateWithoutAlias()
    {
        $object = $this->container->create('\stdClass');
        $this->assertEquals(true,($object instanceof \stdClass));
        $this->assertEquals(array(), $this->container->getSingletons());
    }

    /**
     * @depends testAlias
     */
    public function testCreateWithAlias()
    {
        $this->container->alias('\stdClass','foo');
        $object = $this->container->create('foo');
        $this->assertEquals(true,($object instanceof \stdClass));
        $this->assertEquals(array(), $this->container->getSingletons());
    }

    /**
     * @depends testCreateWithoutAlias
     */
    public function testGetWithoutAlias()
    {
        $object = $this->container->get('\stdClass');
        $this->assertEquals(true,($object instanceof \stdClass));
        $this->assertEquals(array('\stdClass'), $this->container->getSingletons());
        $this->assertEquals($object,$this->container->get('\stdClass'));
    }

    /**
     * @depends testAlias
     * @depends testCreateWithAlias
     */
    public function testGetWithAlias()
    {
        $this->container->alias('\stdClass','foo');
        $object = $this->container->get('foo');
        $this->assertEquals(true,($object instanceof \stdClass));
        $this->assertEquals(array('\stdClass'), $this->container->getSingletons());
        $this->assertEquals($object,$this->container->get('\stdClass'));
        $this->container->alias('\stdClass','bar');
        $this->assertEquals($object,$this->container->get('bar'));
    }

    /**
     * @expectedException \Exception
     */
    public function testAddThrowsException()
    {
        $this->container->add('foo', new \stdClass());
        $this->container->add('foo', new \stdClass());
    }

    /**
     * @depends testCreateWithoutAlias
     * @depends testAdd
     */
    public function testDependencyInjection()
    {
        /** @var \Bonefish\Tests\DependencyInjection\Mocks\Foo $object */
        $object = $this->container->create('\Bonefish\Tests\DependencyInjection\Mocks\Foo');
        $this->assertEquals(true,($object instanceof \Bonefish\Tests\DependencyInjection\Mocks\Foo));
        $this->assertEquals(true,$object->initCalled);
        $this->assertEquals(true,($object->publicPropertyWithInject instanceof Proxy));
        $this->assertEquals(true,($object->getProtectedPropertyWithInject() instanceof Proxy));
        $this->assertEquals(false,$object->publicPropertyNoInject);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotCreateAbstractClass()
    {
        $this->container->create('\Bonefish\Tests\DependencyInjection\Mocks\AbstractFoo');
    }

    /**
     * @depends testDependencyInjection
     * @expectedException \Exception
     */
    public function testInvalidDependencyInjection()
    {
        $this->container->create('\Bonefish\Tests\DependencyInjection\Mocks\Bar');
    }
}
 