<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 01.09.14
 * Time: 20:54
 */

namespace Bonefish\Tests\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testInit()
    {
        $configurationManagerMock = $this->getMock(
            '\Bonefish\Core\ConfigurationManager');
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->with('route.ini');

        $url = \League\Url\UrlImmutable::createFromUrl('');
        $router = new \Bonefish\Router\Router($url);
        $router->configurationManager = $configurationManagerMock;
        $router->__init();
    }

    /**
     * @expectedException \Exception
     */
    public function testRouteOnNonPackage()
    {
        $url = \League\Url\UrlImmutable::createFromUrl('');
        $router = new \Bonefish\Router\Router($url);
        $router->route();
    }

    /**
     * @expectedException \Exception
     */
    public function testRouteDefaultRoute()
    {
        $configuration = new \stdClass();
        $configuration->vendor = 'foo';
        $configuration->package = 'bar';

        $configurationManagerMock = $this->getMock(
            '\Bonefish\Core\ConfigurationManager');
        $configurationManagerMock->expects($this->once())
            ->method('getConfiguration')
            ->with('route.ini')
            ->will($this->returnValue($configuration));

        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('createPackage')
            ->with('foo', 'bar')
            ->will($this->throwException(new \Exception()));

        $url = \League\Url\UrlImmutable::createFromUrl('');
        $router = new \Bonefish\Router\Router($url);
        $router->configurationManager = $configurationManagerMock;
        $router->environment = $enviormentMock;
        $router->__init();
        $router->route();
    }

    /**
     * @expectedException \Exception
     */
    public function testResolveUrl()
    {
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('createPackage')
            ->with('foo', 'bar')
            ->will($this->throwException(new \Exception()));

        $url = \League\Url\UrlImmutable::createFromUrl('bonefish.com/v:foo/p:bar');
        $router = new \Bonefish\Router\Router($url);
        $router->environment = $enviormentMock;
        $router->route();
    }

    public function testCallControllerActionIndex()
    {
        $controllerMock = $this->getMock(
            '\Bonefish\Kickstart\Controller\Controller',
            array()
        );
        $controllerMock->expects($this->once())
            ->method('indexAction');

        $packageMock = $this->getMock(
            '\Bonefish\Core\Package',
            array('includeBootstrap', 'getController'),
            array('Bonefish', 'Kickstart')
        );
        $packageMock->expects($this->any())
            ->method('getController')
            ->will($this->returnValue($controllerMock));

        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('createPackage')
            ->with('Bonefish', 'Kickstart')
            ->will($this->returnValue($packageMock));

        $url = \League\Url\UrlImmutable::createFromUrl('bonefish.com/v:Bonefish/p:Kickstart');
        $router = new \Bonefish\Router\Router($url);
        $router->environment = $enviormentMock;
        $router->route();
    }

    public function testCallControllerAction()
    {
        $controllerMock = $this->getMock(
            '\Bonefish\Kickstart\Controller\Controller',
            array()
        );
        $controllerMock->expects($this->once())
            ->method('unitAction')
            ->with('foo', 'bar');

        $packageMock = $this->getMock(
            '\Bonefish\Core\Package',
            array('includeBootstrap', 'getController'),
            array('Bonefish', 'Kickstart')
        );
        $packageMock->expects($this->any())
            ->method('getController')
            ->will($this->returnValue($controllerMock));

        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->once())
            ->method('createPackage')
            ->with('Bonefish', 'Kickstart')
            ->will($this->returnValue($packageMock));

        $url = \League\Url\UrlImmutable::createFromUrl('bonefish.com/v:Bonefish/p:Kickstart/a:unit/foo:foo/bar:bar');
        $router = new \Bonefish\Router\Router($url);
        $router->environment = $enviormentMock;
        $router->route();
    }
}
 