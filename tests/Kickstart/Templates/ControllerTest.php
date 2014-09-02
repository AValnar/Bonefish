<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 21:24
 */

namespace Bonefish\Tests\Kickstart\Templates;


use Bonefish\Kickstart\Templates\Controller;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $controller = new Controller();
        $controller->indexAction();
    }
}
 