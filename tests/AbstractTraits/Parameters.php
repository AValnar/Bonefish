<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 10.10.2014
 * Time: 21:00
 */

namespace Bonefish\Tests\AbstractTraits;

use Bonefish\AbstractTraits\Parameters;

class ParametersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parameters
     */
    public $sut;

    public function setUp()
    {
        $this->sut = $this->getMockForTrait('\Bonefish\AbstractTraits\Parameters');
    }

    /**
     * @param $getter
     * @param $setter
     * @param $value
     */
    public function testGetterAndSetter($getter, $setter, $value)
    {
        $this->sut->{$setter}($value);
        $this->assertThat($this->sut->{$getter}(), $this->equalTo($value));
    }

    public function getterAndSetterProvider()
    {
        return array(
            array('getParameters', 'setParameters', array('el'))
        );
    }
}
 