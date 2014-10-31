<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 10.10.2014
 * Time: 21:45
 */

namespace Bonefish\Tests\ACL\Profiles;


use Bonefish\ACL\Profiles\AdminProfile;

class AdminProfileTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AdminProfile
     */
    public $profile;

    public function setUp()
    {
        $this->profile = new AdminProfile();
    }

    public function testGetPermissions()
    {
        $this->assertThat($this->profile->getPermissions(),$this->equalTo(array()));
    }
}
 