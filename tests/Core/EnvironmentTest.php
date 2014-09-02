<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 20:01
 */

namespace Bonefish\Tests\Core;


class EnvironmentTest extends \PHPUnit_Framework_TestCase
{

    protected $container;
    protected $environment;

    public function setUp()
    {
        $this->container = $this->getMock('\Bonefish\DependencyInjection\Container');
        $this->environment = new \Bonefish\Core\Environment();
        $this->environment->container = $this->container;
    }

    /**
     * @dataProvider getterAndSetterProvider
     */
    public function testGetterAndSetter($getter, $setter, $value)
    {
        $this->environment->{$setter}($value);
        $this->assertThat($this->environment->{$getter}(), $this->equalTo($value));
    }

    /**
     * @dataProvider fullGetterAndSetterProvider
     * @depends      testGetterAndSetter
     */
    public function testGetFullPath($getter, $setter)
    {
        $value = 'foo';
        $this->environment->setBasePath('');
        $this->environment->{$setter}($value);
        $this->assertThat($this->environment->{$getter}(), $this->equalTo($value));
    }

    public function getterAndSetterProvider()
    {
        return array(
            array('getBasePath', 'setBasePath', 'foo'),
            array('getModulePath', 'setModulePath', 'foo'),
            array('getConfigurationPath', 'setConfigurationPath', 'foo')
        );
    }

    public function testCreatePackage()
    {
        $vendor = 'foo';
        $package = 'bar';
        $this->container->expects($this->once())
            ->method('create')
            ->with('\Bonefish\Core\Package', array($vendor, $package))
            ->will($this->returnValue('foobar'));
        $return = $this->environment->createPackage($vendor, $package);
        $this->assertThat($return, $this->equalTo('foobar'));
    }

    public function testGetAllPackages()
    {
        $this->container->expects($this->exactly(2))
            ->method('create')
            ->with('\Bonefish\Core\Package')
            ->will($this->returnValue('foobar'));
        $this->environment->setBasePath(__DIR__ . '\..\..\modules');
        $return = $this->environment->getAllPackages();
        $this->assertThat($return, $this->equalTo(array('foobar', 'foobar')));
    }

    public function fullGetterAndSetterProvider()
    {
        return array(
            array('getFullModulePath', 'setModulePath'),
            array('getFullConfigurationPath', 'setConfigurationPath')
        );
    }
}
 