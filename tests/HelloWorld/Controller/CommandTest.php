<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 21:48
 */

namespace Bonefish\Tests\HelloWorld\Controller;


use Bonefish\HelloWorld\Controller\Command;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $this->expectOutputString('[mHello World[0m
');
        $controller = new Command();
        $controller->mainCommand();
    }
}
 