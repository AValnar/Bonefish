<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 21:50
 */

namespace Bonefish\Tests\HelloWorld\Controller;


use Bonefish\HelloWorld\Controller\Controller;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $this->expectOutputString('Hello World');
        $controller = new Controller();
        $controller->indexAction();
    }

    public function testHelloAction()
    {
        $this->expectOutputString('Hello Steve!');
        $controller = new Controller();
        $controller->helloAction('Steve');
    }
}
 