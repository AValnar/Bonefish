<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 20:41
 */

namespace Bonefish\Tests\Core;


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
            ->with('Bonefish\HelloWorld\Controller\Command');
        $this->package->container = $container;
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('getFullPackagePath')
            ->will($this->returnValue(''));
        $this->package->environment = $enviormentMock;
        $configurationManagerMock = $this->getMockBuilder('\Bonefish\Core\ConfigurationManager')
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue(array(array('Bonefish',array('HelloWorld')))));
        $this->package->configurationManager = $configurationManagerMock;
        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
        $this->package->autoloader = $autoloader;
        $this->package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
    }

    public function testMapAutoloaderTwice()
    {
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('getFullPackagePath')
            ->will($this->returnValue(''));
        $this->package->environment = $enviormentMock;
        $configurationManagerMock = $this->getMockBuilder('\Bonefish\Core\ConfigurationManager')
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue(array(array('Bonefish',array('HelloWorld')))));
        $this->package->configurationManager = $configurationManagerMock;
        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
        $this->package->autoloader = $autoloader;
        $this->package->mapAutoloader();
        $this->package->mapAutoloader();
    }

    public function testGetControllerTriesToAutoload()
    {
        $container = $this->getMock('\Bonefish\DependencyInjection\Container');
        $container->expects($this->once())
            ->method('get')
            ->with('foo\bar\Controller\Command');
        $this->package->container = $container;
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('getFullPackagePath')
            ->will($this->returnValue(''));
        $this->package->environment = $enviormentMock;
        $configurationManagerMock = $this->getMockBuilder('\Bonefish\Core\ConfigurationManager')
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue(array(array('Bonefish',array('HelloWorld')))));
        $this->package->configurationManager = $configurationManagerMock;
        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
        $autoloader->expects($this->once())
            ->method('loadClass');
        $this->package->autoloader = $autoloader;
        $this->package->setVendor('foo');
        $this->package->setName('bar');
        $this->package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
    }

    public function testBootstrapIsIncludedAndMapped()
    {
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->exactly(2))
            ->method('getFullPackagePath')
            ->will($this->returnValue(__DIR__.'/../../modules'));
        $this->package->environment = $enviormentMock;
        $configurationManagerMock = $this->getMockBuilder('\Bonefish\Core\ConfigurationManager')
            ->disableOriginalConstructor()
            ->getMock();
        $config = new \stdClass();
        $config->autoload = true;
        $config->classPrefix = 'Bonefish\HelloWorld';
        $config->classPath = 'Bonefish/HelloWorld';
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($config));
        $this->package->configurationManager = $configurationManagerMock;
        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
        $autoloader->expects($this->once())
            ->method('addNamespace');
        $this->package->autoloader = $autoloader;
        $this->package->mapAutoloader();
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

    public function getterAndSetterProvider()
    {
        return array(
            array('getName', 'setName', 'foo'),
            array('getVendor', 'setVendor', 'foo')
        );
    }
}
 