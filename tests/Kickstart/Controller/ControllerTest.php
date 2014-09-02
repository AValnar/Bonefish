<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 21:50
 */

namespace Bonefish\Tests\Kickstart\Controller;


use Bonefish\Kickstart\Controller\Controller;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $this->expectOutputString('Please use the command line tool to use Bonefish/Kickstart');
        $controller = new Controller();
        $controller->indexAction();
    }

    public function testUnitAction()
    {
        $controller = new Controller();
        $this->assertThat($controller->unitAction('foo','bar'),$this->equalTo('foobar'));
    }
}
 