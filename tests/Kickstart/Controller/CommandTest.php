<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 21:48
 */

namespace Bonefish\Tests\Kickstart\Controller;


use Bonefish\Kickstart\Controller\Command;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $kickstarterMock = $this->getMock(
            '\Bonefish\Kickstart\Kickstart');
        $kickstarterMock->expects($this->once())
            ->method('module')
            ->with('foo','bar');

        $containerMock = $this->getMock(
            '\Bonefish\DependencyInjection\Container');
        $containerMock->expects($this->once())
            ->method('get')
            ->with('\Bonefish\Kickstart\Kickstart')
            ->will($this->returnValue($kickstarterMock));

        $controller = new Command();
        $controller->container = $containerMock;
        $controller->moduleCommand('foo','bar');
        $this->assertThat($controller->unitCommand('foo','bar'),$this->equalTo('foobar'));
    }
}
 