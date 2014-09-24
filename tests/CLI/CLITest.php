<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 01.09.14
 * Time: 19:51
 */

namespace Bonefish\Tests\CLI;

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
[m[91mVendor[0m[m: Bonefish [91mModule[0m[m: HelloWorld[0m
[m----------------------------------------------------------------------------------------------------[0m
[mBonefish HelloWorld main[0m
[mBonefish HelloWorld greet[0m
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
[m[91mVendor[0m[m: Bonefish [91mModule[0m[m: HelloWorld[0m
[m----------------------------------------------------------------------------------------------------[0m
[mBonefish HelloWorld main[0m
[mBonefish HelloWorld greet[0m
[m[0m
');
        $packageMock = $this->createPackageMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('Bonefish', 'HelloWorld')
            ->will($this->returnValue($packageMock));

        $cli = new \Bonefish\CLI\CLI(array('', 'Bonefish', 'HelloWorld', 'help'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    public function testPrettyPrint()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[92m[0m
[mmainCommand[0m
[m[0m
[mMethod Parameters:[0m
');
        $packageMock = $this->createPackageMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('Bonefish', 'HelloWorld')
            ->will($this->returnValue($packageMock));

        $cli = new \Bonefish\CLI\CLI(array('', 'Bonefish', 'HelloWorld', 'main', 'help'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    public function testExecuteCommand()
    {
        $this->expectOutputString('[92mWelcome to Bonefish![0m
[m[0m
[mHello World[0m
');
        $packageMock = $this->createPackageMock();
        $enviormentMock = $this->getMockBuilder('\Bonefish\Core\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $enviormentMock->expects($this->any())
            ->method('createPackage')
            ->with('Bonefish', 'HelloWorld')
            ->will($this->returnValue($packageMock));

        $cli = new \Bonefish\CLI\CLI(array('', 'Bonefish', 'HelloWorld', 'main', 'test', 'foo'));
        $cli->environment = $enviormentMock;
        $cli->execute();
    }

    protected function createPackageMock()
    {
        $autoloader = new \Bonefish\Autoloader\Autoloader();
        $autoloader->addNamespace('Bonefish\HelloWorld','Packages/Bonefish/HelloWorld');
        $autoloader->register();

        $packageMock = $this->getMock(
            '\Bonefish\Core\Package',
            array('getController'),
            array('Bonefish', 'HelloWorld')
        );
        $packageMock->expects($this->any())
            ->method('getController')
            ->will($this->returnValue(new \Bonefish\HelloWorld\Controller\Command()));

        return $packageMock;
    }
}
 