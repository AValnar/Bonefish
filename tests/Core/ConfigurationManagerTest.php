<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 02.09.14
 * Time: 19:43
 */

namespace Bonefish\Tests\Core;


class ConfigurationManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $enviormentMock;

    protected $configurationManager;

    public function setUp()
    {
        file_put_contents(__DIR__.'/phpunittest.ini', 'test = 1');

        $this->enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();

        $this->configurationManager = new \Bonefish\Core\ConfigurationManager();
        $this->configurationManager->environment = $this->enviormentMock;
    }

    public function testGetConfiguration()
    {
        $this->enviormentMock->expects($this->once())
            ->method('getFullConfigurationPath')
            ->will($this->returnValue(__DIR__));
        $this->assertThat($this->configurationManager->getConfiguration('phpunittest.ini')->test, $this->equalTo(1));
    }

    public function testGetConfigurationInPath()
    {
        $this->assertThat($this->configurationManager->getConfiguration(__DIR__.'/phpunittest.ini',true)->test, $this->equalTo(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetConfigurationNotExist()
    {
        $this->enviormentMock->expects($this->once())
            ->method('getFullConfigurationPath')
            ->will($this->returnValue(__DIR__));
        $this->configurationManager->getConfiguration('nonexistant.ini');
    }

    public function testGetConfigurationCached()
    {
        $this->enviormentMock->expects($this->once())
            ->method('getFullConfigurationPath')
            ->will($this->returnValue(__DIR__));
        $this->assertThat($this->configurationManager->getConfiguration('phpunittest.ini')->test, $this->equalTo(1));
        $this->assertThat($this->configurationManager->getConfiguration('phpunittest.ini')->test, $this->equalTo(1));
    }

    public function tearDown()
    {
        unlink(__DIR__.'/phpunittest.ini');
    }
}