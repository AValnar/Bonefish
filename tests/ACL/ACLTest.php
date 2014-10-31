<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 10.10.2014
 * Time: 21:00
 */

namespace Bonefish\Tests\ACL;

use Bonefish\Tests\ACL\Mocks\Controller;
use Bonefish\Tests\ACL\Mocks\ProfileOne;
use Bonefish\Tests\ACL\Mocks\ProfileTwo;

class ACLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Bonefish\ACL\ACL
     */
    public $acl;

    /**
     * @var ProfileOne
     */
    public $profileOne;

    /**
     * @var ProfileTwo
     */
    public $profileTwo;

    /**
     * @var Controller
     */
    public $controller;

    public function setUp()
    {
        $this->acl = new \Bonefish\ACL\ACL();
        $this->profileOne = new ProfileOne();
        $this->profileTwo = new ProfileTwo();
        $this->controller = new Controller();
    }

    public function testIsPrivate()
    {
        $expected = array(
            'privateMethod' => true,
            'includeMethod' => true,
            'excludeMethod' => false,
            'includeMethodTwo' => false,
            'mixedMethod' => false,
            'openMethod' => false
        );

        $this->assertThat($this->acl->isPrivate($this->controller), $this->equalTo(true));

        foreach($expected as $test => $result) {
            $this->assertThat($this->acl->isPrivate($this->controller,$test), $this->equalTo($result));
        }

    }

    public function testIsAllowedProfileOne()
    {
        $this->acl->setProfile($this->profileOne);

        $expected = array(
            'privateMethod' => false,
            'includeMethod' => true,
            'excludeMethod' => false,
            'includeMethodTwo' => true,
            'mixedMethod' => true,
            'openMethod' => true
        );

        $this->assertThat($this->acl->isAllowed($this->controller), $this->equalTo(false));

        foreach($expected as $test => $result) {
            $this->assertThat($this->acl->isAllowed($this->controller,$test), $this->equalTo($result));
        }

    }

    public function testIsAllowedProfileTwo()
    {
        $this->acl->setProfile($this->profileTwo);

        $expected = array(
            'privateMethod' => false,
            'includeMethod' => false,
            'excludeMethod' => true,
            'includeMethodTwo' => true,
            'mixedMethod' => false,
            'openMethod' => true
        );

        $this->assertThat($this->acl->isAllowed($this->controller), $this->equalTo(false));

        foreach($expected as $test => $result) {
            $this->assertThat($this->acl->isAllowed($this->controller,$test), $this->equalTo($result));
        }

    }

    /**
     * @dataProvider getterAndSetterProvider
     */
    public function testGetterAndSetter($getter, $setter, $value)
    {
        $this->acl->{$setter}($value);
        $this->assertThat($this->acl->{$getter}(), $this->equalTo($value));
    }

    public function getterAndSetterProvider()
    {
        return array(
            array('getProfile', 'setProfile', $this->getMockForAbstractClass('\Bonefish\ACL\Profile'))
        );
    }
}
 