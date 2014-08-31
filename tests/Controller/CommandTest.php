<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 31.08.14
 * Time: 17:01
 */

namespace tests\Controller;


class CommandTest extends \PHPUnit_Framework_TestCase {

    public function testConstruct()
    {
        $controller = new \Bonefish\Controller\Command('test');
        $this->assertThat($controller->getBaseDir(),$this->equalTo('test'));
    }
}
 