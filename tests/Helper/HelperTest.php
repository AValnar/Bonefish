<?php
/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-05-06
 * @package    Bonefish
 * @subpackage Helper
 */

namespace Bonefish\Tests\Helper;

use Bonefish\Helper\Helper;

class InterpolateMock
{
    public function __toString()
    {
        return 'foo';
    }
}

class HelperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Helper
     */
    protected $helper;

    public function setUp()
    {
        $this->helper = new Helper();
    }

    /**
     * @dataProvider providerEvalTrueFalse
     */
    public function testEvalTrueOrFalseStringToBoolean($given, $expected)
    {
        $this->assertEquals($expected, $this->helper->evalTrueOrFalseStringToBoolean($given));
    }

    /**
     * @dataProvider providerEvalBool
     */
    public function testEvalBool($given, $expected)
    {
        $this->assertEquals($expected, $this->helper->evalToBoolean($given));
    }

    /**
     * @dataProvider providerValidID
     */
    public function testIsValidID($given, $expected)
    {
        $this->assertEquals($expected, $this->helper->isValidID($given));
    }

    /**
     * @dataProvider providerLazyS
     */
    public function testLazyS($given, $expected)
    {
        $this->assertEquals($expected, $this->helper->lazyS($given));
    }

    /**
     * @dataProvider providerCustomNlToBr
     */
    public function testCustomNl2br($given, $expected)
    {
        $this->assertEquals($expected, $this->helper->customNl2br($given));
    }

    /**
     * @dataProvider providerConvertBoolToInt
     */
    public function testConvertBoolToInt($given, $expected)
    {
        $this->assertEquals($expected, $this->helper->convertBooleanToInt($given));
    }

    public function testBacktrace()
    {
        $this->assertEquals(true, is_string($this->helper->stacktraceAsString()));
    }

    /**
     * @dataProvider providerInterpolate
     */
    public function testInterpolate($given, $expected, $arguments)
    {
        $this->assertEquals(serialize($expected), serialize($this->helper->interpolate($given, $arguments)));
    }

    public function providerInterpolate()
    {
        return array(
            array('test', 'test', array()),
            array('test {test}', 'test foo', array('test' => 'foo')),
            array('test {test}', "test " . print_r(array('foo', 'bar'), true), array('test' => array('foo', 'bar'))),
            array('test {test}', 'test {test}', array('test' => new \stdClass())),
            array('test {test}', 'test foo', array('test' => new InterpolateMock()))
        );
    }

    public function providerEvalTrueFalse()
    {
        return array(
            array('true', true),
            array('false', false),
            array('foo', 'foo'),
            array('bar', 'bar')
        );
    }

    public function providerEvalBool()
    {
        return array(
            array('true', true),
            array('TRUE', true),
            array('tRUe', true),
            array('on', true),
            array(true, true),
            array(1, true),
            array('foo', false),
            array('bar', false),
            array('y', true),
            array('yes', true),
            array('false', false),
            array('Y', true)
        );
    }

    public function providerValidID()
    {
        return array(
            array(1, true),
            array(1337, true),
            array(-2, false),
            array(0, false),
            array('bar', false),
        );
    }

    public function providerLazyS()
    {
        return array(
            array(1, ''),
            array(2, 's'),
            array(3, 's'),
            array(1337, 's'),
            array(-2, 's'),
            array(0, 's'),
            array('bar', 's')
        );
    }

    public function providerCustomNlToBr()
    {
        return array(
            array("Hello \n Hello", "Hello <br /> Hello"),
            array("Hello <br /> Hello", "Hello <br /> Hello"),
            array("Hello Hello", "Hello Hello")
        );
    }

    public function providerConvertBoolToInt()
    {
        return array(
            array('true', 0),
            array('TRUE', 0),
            array('tRUe', 0),
            array('on', 0),
            array(true, 1),
            array(1, 0),
            array('foo', 0),
            array('bar', 0),
            array('y', 0),
            array('yes', 0),
            array('false', 0),
            array('Y', 0)
        );
    }

}
 