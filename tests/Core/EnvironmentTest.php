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
            array('getPackagePath', 'setPackagePath', 'foo'),
            array('getCachePath', 'setCachePath', 'foo'),
            array('getConfigurationPath', 'setConfigurationPath', 'foo'),
            array('getPackage', 'setPackage', 'foo')
        );
    }

    public function fullGetterAndSetterProvider()
    {
        return array(
            array('getFullPackagePath', 'setPackagePath'),
            array('getFullConfigurationPath', 'setConfigurationPath'),
            array('getFullCachePath', 'setCachePath')
        );
    }
}
 