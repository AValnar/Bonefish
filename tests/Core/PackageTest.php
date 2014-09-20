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
        $this->package = new \Bonefish\Core\Package('Bonefish', 'Kickstart');
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

//    public function testGetController()
//    {
//        $this->markTestSkipped();
//        $container = $this->getMock('\Bonefish\DependencyInjection\Container');
//        $container->expects($this->once())
//            ->method('get')
//            ->with('Bonefish\Kickstart\Controller\Command');
//        $this->package->container = $container;
//        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $enviormentMock->expects($this->once())
//            ->method('getFullModulePath')
//            ->will($this->returnValue(''));
//        $this->package->environment = $enviormentMock;
//        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
//        $this->package->autoloader = $autoloader;
//        $this->package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
//    }
//
//    public function testMapAutoloaderTwice()
//    {
//        $this->markTestSkipped();
//        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $enviormentMock->expects($this->once())
//            ->method('getFullModulePath')
//            ->will($this->returnValue(''));
//        $this->package->environment = $enviormentMock;
//        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
//        $this->package->autoloader = $autoloader;
//        $this->package->mapAutoloader();
//        $this->package->mapAutoloader();
//    }
//
//    public function testGetControllerTriesToAutoload()
//    {
//        $this->markTestSkipped();
//        $container = $this->getMock('\Bonefish\DependencyInjection\Container');
//        $container->expects($this->once())
//            ->method('get')
//            ->with('foo\bar\Controller\Command');
//        $this->package->container = $container;
//        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $enviormentMock->expects($this->once())
//            ->method('getFullModulePath')
//            ->will($this->returnValue(''));
//        $this->package->environment = $enviormentMock;
//        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
//        $autoloader->expects($this->once())
//            ->method('loadClass');
//        $this->package->autoloader = $autoloader;
//        $this->package->setVendor('foo');
//        $this->package->setName('bar');
//        $this->package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
//    }
//
//    public function testBootstrapIsIncludedAndMapped()
//    {
//        $this->markTestSkipped();
//        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $enviormentMock->expects($this->exactly(2))
//            ->method('getFullModulePath')
//            ->will($this->returnValue(__DIR__.'/../../modules'));
//        $this->package->environment = $enviormentMock;
//        $autoloader = $this->getMock('\Bonefish\Autoloader\Autoloader');
//        $autoloader->expects($this->once())
//            ->method('addNamespace');
//        $this->package->autoloader = $autoloader;
//        $this->package->mapAutoloader();
//    }
//
//    public function testGetPackageUrlPath()
//    {
//        $this->markTestSkipped();
//        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $enviormentMock->expects($this->once())
//            ->method('getModulePath')
//            ->will($this->returnValue(__DIR__));
//        $this->package->environment = $enviormentMock;
//        $path = $this->package->getPackageUrlPath();
//        $this->assertThat($path,$this->equalTo(__DIR__.'/Bonefish/Kickstart'));
//    }

    public function getterAndSetterProvider()
    {
        return array(
            array('getName', 'setName', 'foo'),
            array('getVendor', 'setVendor', 'foo')
        );
    }
}
 