<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 21:07
 */

namespace Bonefish\Tests\Kickstart;


use Bonefish\Kickstart\Kickstart;

class KickstartTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('getFullModulePath')
            ->will($this->returnValue(__DIR__));
        $kickstarter = new Kickstart();
        $kickstarter->environment = $enviormentMock;
        $kickstarter->module('bar', 'foo');
        $this->assertThat(file_exists(__DIR__ . '/foo/bar/Controller/Command.php'), $this->equalTo(true));
        $this->assertThat(file_exists(__DIR__ . '/foo/bar/Controller/Controller.php'), $this->equalTo(true));
        $this->assertThat(file_exists(__DIR__ . '/foo/bar/bootstrap.php'), $this->equalTo(true));
    }

    public function tearDown()
    {
        $files = array('/foo/bar/Controller/Command.php', '/foo/bar/Controller/Controller.php', '/foo/bar/bootstrap.php');
        foreach ($files as $file) {
            if (file_exists(__DIR__ . $file)) {
                unlink(__DIR__ . $file);
            }
        }
        rmdir(__DIR__ . '/foo/bar/Controller');
        rmdir(__DIR__ . '/foo/bar');
        rmdir(__DIR__ . '/foo');
    }
}
 