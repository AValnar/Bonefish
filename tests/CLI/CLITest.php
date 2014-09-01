<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 01.09.14
 * Time: 19:51
 */

namespace Bonefish\Tests\CLI;

include __DIR__.'../../modules/Bonefish/Kickstart/Controller/Command.php';

class CLITest extends \PHPUnit_Framework_TestCase
{

    public function testInvalidArgsEmpty()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[mIncomplete command![0m
');
        $cli = new \Bonefish\CLI\CLI(array());
        $cli->execute();
    }

    public function testInvalidArgsMissingPackage()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[mIncomplete command![0m
');
        $cli = new \Bonefish\CLI\CLI(array('', 'foo'));
        $cli->execute();
    }

    public function testInvalidArgsMissingAction()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[mIncomplete command![0m
');
        $cli = new \Bonefish\CLI\CLI(array('', 'foo', 'bar'));
        $cli->execute();
    }

    public function testInvalidArgsInvalidAction()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[mInvalid action![0m
');
        $packageMock = $this->getMockBuilder('\Bonefish\Core\Package')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('foo', 'bar')
            ->will($this->returnValue($packageMock));
        $cli = new \Bonefish\CLI\CLI(array('', 'foo', 'bar', 'baz'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    public function testInvalidArgsInvalidPackage()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[mInvalid command![0m
');
        $packageMock = $this->getMockBuilder('\Bonefish\Core\Package')
            ->disableOriginalConstructor()
            ->getMock();
        $packageMock->expects($this->any())
            ->method('getController')
            ->will($this->throwException(new \Exception()));
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('foo', 'bar')
            ->will($this->returnValue($packageMock));
        $cli = new \Bonefish\CLI\CLI(array('', 'foo', 'bar', 'baz'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    public function testHelpGlobal()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[mThe following commands are present in your system:[0m
[m[91mVendor[0m[m: Bonefish [91mModule[0m[m: Kickstart[0m
[m----------------------------------------------------------------------------------------------------[0m
[mBonefish Kickstart module[0m
[mBonefish Kickstart unit[0m
[m[0m
');
        $packageMock = $this->createPackageMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('getAllPackages')
            ->will($this->returnValue(array($packageMock)));

        $cli = new \Bonefish\CLI\CLI(array('', 'help'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    public function testHelpPackage()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[m[91mVendor[0m[m: Bonefish [91mModule[0m[m: Kickstart[0m
[m----------------------------------------------------------------------------------------------------[0m
[mBonefish Kickstart module[0m
[mBonefish Kickstart unit[0m
[m[0m
');
        $packageMock = $this->createPackageMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('Bonefish','Kickstart')
            ->will($this->returnValue($packageMock));

        $cli = new \Bonefish\CLI\CLI(array('','Bonefish','Kickstart', 'help'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    public function testPrettyPrint()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[37m[0m
[mBonefish\Kickstart\Controller\Command::unitCommand()[0m
[m[0m
[mMethod Parameters:[0m
[m[94mstring $test[0m[m = \'\'[0m
[m[94mvar[0m[m[0m
');
        $packageMock = $this->createPackageMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('Bonefish','Kickstart')
            ->will($this->returnValue($packageMock));

        $cli = new \Bonefish\CLI\CLI(array('', 'Bonefish','Kickstart','unit','help'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    public function testExecuteCommand()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
');
        $packageMock = $this->createPackageMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('Bonefish','Kickstart')
            ->will($this->returnValue($packageMock));

        $cli = new \Bonefish\CLI\CLI(array('', 'Bonefish','Kickstart','unit','test','foo'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    protected function createPackageMock()
    {
        $packageMock = $this->getMock(
            '\Bonefish\Core\Package',
            array('includeBootstrap', 'getController'),
            array('Bonefish', 'Kickstart')
        );
        $packageMock->expects($this->any())
            ->method('getController')
            ->will($this->returnValue(new \Bonefish\Kickstart\Controller\Command()));

        return $packageMock;
    }
}
 