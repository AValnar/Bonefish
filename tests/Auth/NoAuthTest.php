<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 22.03.2015
 * Time: 09:53
 */

namespace Bonefish\Tests\Auth;


use Bonefish\Auth\NoAuth;
use Bonefish\DependencyInjection\Container;
use Bonefish\DI\IContainer;

class NoAuthTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var NoAuth
     */
    public $sut;

    /**
     * @var IContainer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $container;

    public function setUp()
    {
        $this->sut = new NoAuth();
        $this->container = $this->getMock('\Bonefish\DependencyInjection\Container');
        $this->sut->container = $this->container;
    }

    public function testAuthenticate()
    {
        $this->assertFalse($this->sut->authenticate());
    }

    public function testGetProfile()
    {
        $this->container->expects($this->once())
            ->method('create')
            ->will($this->returnArgument(0));

        $this->assertThat(
            $this->sut->getProfile(),
            $this->equalTo('\Bonefish\ACL\Profiles\PublicProfile')
        );
    }

    public function testLogout()
    {
        $this->assertTrue($this->sut->logout());
    }
}