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
        file_put_contents('/phpunittest.ini', 'test = 1');

        $this->enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $this->enviormentMock->expects($this->once())
            ->method('getFullConfigurationPath')
            ->will($this->returnValue(''));

        $this->configurationManager = new \Bonefish\Core\ConfigurationManager();
        $this->configurationManager->environment = $this->enviormentMock;
    }

    public function testGetConfiguration()
    {
        $this->assertThat($this->configurationManager->getConfiguration('phpunittest.ini')->test, $this->equalTo(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetConfigurationNotExist()
    {
        $this->configurationManager->getConfiguration('nonexistant.ini');
    }

    public function testGetConfigurationCached()
    {
        $this->assertThat($this->configurationManager->getConfiguration('phpunittest.ini')->test, $this->equalTo(1));
        $this->assertThat($this->configurationManager->getConfiguration('phpunittest.ini')->test, $this->equalTo(1));
    }

    public function tearDown()
    {
        unlink('/phpunittest.ini');
    }
}