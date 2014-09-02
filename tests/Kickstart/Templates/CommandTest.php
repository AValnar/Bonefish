<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 21:24
 */

namespace Bonefish\Tests\Kickstart\Templates;


use Bonefish\Kickstart\Templates\Command;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $controller = new Command();
        $controller->mainCommand();
    }
}
 