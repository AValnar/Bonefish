<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 20:41
 */

namespace Bonefish\Tests\Core;


use Bonefish\Core\Package;

class PackageTest extends \PHPUnit_Framework_TestCase
{

    protected $package;

    public function setUp()
    {
        $this->package = new \Bonefish\Core\Package('Bonefish', 'HelloWorld');
    }

    public function testConstruct()
    {
        $package = new \Bonefish\Core\Package('foo', 'bar');
        $this->assertThat($package->getVendor(), $this->equalTo('foo'));
        $this->assertThat($package->getName(), $this->equalTo('bar'));
    }

    /**
     * @dataProvider getterAndSetterProvider
     */
    public function testGetterAndSetter($getter, $setter, $value)
    {
        $this->package->{$setter}($value);
        $this->assertThat($this->package->{$getter}(), $this->equalTo($value));
    }

    public function testGetController()
    {
        $container = $this->getMock('\Bonefish\DependencyInjection\Container');
        $container->expects($this->once())
            ->method('get')
            ->with('\Bonefish\HelloWorld\Controller\Command');
        $this->package->container = $container;
        $this->package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
    }

    public function testGetPackageUrlPath()
    {
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('getPackagePath')
            ->will($this->returnValue(__DIR__));
        $this->package->environment = $enviormentMock;
        $path = $this->package->getPackageUrlPath();
        $this->assertThat($path,$this->equalTo(__DIR__.'/Bonefish/HelloWorld'));
    }

    public function testNoConfiguration()
    {
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->exactly(1))
            ->method('getFullPackagePath')
            ->will($this->returnValue(__DIR__.'/../../modules'));
        $this->package->environment = $enviormentMock;
        $configurationManagerMock = $this->getMockBuilder('\Bonefish\Core\ConfigurationManager')
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->will($this->throwException(new \Exception()));
        $this->package->configurationManager = $configurationManagerMock;
        $config = $this->package->getConfiguration();
        $this->assertThat($config,$this->equalTo(false));
    }

    public function testConfigurationCache()
    {
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->exactly(1))
            ->method('getFullPackagePath')
            ->will($this->returnValue(__DIR__.'/../../modules'));
        $this->package->environment = $enviormentMock;
        $configurationManagerMock = $this->getMockBuilder('\Bonefish\Core\ConfigurationManager')
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->will($this->throwException(new \Exception()));
        $this->package->configurationManager = $configurationManagerMock;
        $config = $this->package->getConfiguration();
        $this->assertThat($config,$this->equalTo(false));
        $config = $this->package->getConfiguration();
        $this->assertThat($config,$this->equalTo(false));
    }

    public function testAutoload()
    {
        $package = new Package('test','test',array('autoload' => true));
        $autoloader = $this->getMockBuilder('\Bonefish\Autoloader\Autoloader')
            ->disableOriginalConstructor()
            ->getMock();
        $autoloader->expects($this->exactly(2))
            ->method('addNamespace')
            ->will($this->returnValue(true));
        $package->autoloader = $autoloader;
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->exactly(2))
            ->method('getFullPackagePath')
            ->will($this->returnValue(__DIR__));
        $package->environment = $enviormentMock;
        $package->__init();
    }

    public function getterAndSetterProvider()
    {
        return array(
            array('getName', 'setName', 'foo'),
            array('getPath', 'setPath', 'foo'),
            array('getVendor', 'setVendor', 'foo')
        );
    }
}
 