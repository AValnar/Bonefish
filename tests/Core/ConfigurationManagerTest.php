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

        $config = new \stdClass();
        $config->test = 1;

        $this->enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();

        $this->enviormentMock->expects($this->any())
            ->method('getFullConfigurationPath')
            ->will($this->returnValue(__DIR__));

        $neon = $this->getMockBuilder('\Nette\Neon\Neon')
            ->disableOriginalConstructor()
            ->getMock();

        $neon->expects($this->any())
            ->method('decode')
            ->will($this->returnValue($config));

        $this->configurationManager = new \Bonefish\Core\ConfigurationManager();
        $this->configurationManager->environment = $this->enviormentMock;
        $this->configurationManager->neon = $neon;
    }

    public function testGetConfiguration()
    {
        $this->configurationManager->getConfiguration('phpunittest.ini');
    }

    public function testGetConfigurationInPath()
    {
        $this->configurationManager->getConfiguration(__DIR__.'/phpunittest.ini',true);
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
        $this->configurationManager->getConfiguration('phpunittest.ini');
        $this->configurationManager->getConfiguration('phpunittest.ini');
    }

    public function tearDown()
    {
        unlink(__DIR__.'/phpunittest.ini');
    }
}