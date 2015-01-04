<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 21.09.14
 * Time: 11:05
 */

namespace Bonefish\Tests\CLI;


use Bonefish\CLI\Printer;
use Bonefish\Tests\CLI\Mocks\Dummy;

class PrinterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Dummy
     */
    protected $dummy;

    /**
     * @var Printer
     */
    protected $printer;

    public function setup()
    {
        $this->dummy = new Dummy();
        $this->printer = new Printer();
    }

    /**
     * @dataProvider dataProvider
     * @param $method
     * @param $expected
     */
    public function testPrettyMethod($method, $expected)
    {
        $ret = $this->printer->prettyMethod($this->dummy, $method, TRUE, TRUE, TRUE);
        $this->assertThat($ret,$this->equalTo($expected));
    }

    public function dataProvider()
    {
        return array(
            array('noParameterNoDoc', 'noParameterNoDoc' . PHP_EOL),
            array('noParameterWithDoc', '<light_green>noParameterWithDoc comment</light_green>
noParameterWithDoc' . PHP_EOL),
            array('parameterNoDoc', 'parameterNoDoc

Method Parameters:
<light_blue>a</light_blue>' . PHP_EOL),
            array('parameterWithDoc', '<light_green>parameterWithDoc comment</light_green>
parameterWithDoc

Method Parameters:
<light_blue>$a</light_blue>' . PHP_EOL),
            array('parameterOptionalNoDoc', 'parameterOptionalNoDoc

Method Parameters:
<light_blue>a</light_blue> = true' . PHP_EOL),
            array('parameterOptionalWithDoc', '<light_green>parameterWithDoc comment</light_green>
parameterOptionalWithDoc

Method Parameters:
<light_blue>bool $a</light_blue> = true' . PHP_EOL),
            array('mixedExample', '<light_green>mixedExample comment</light_green>
mixedExample

Method Parameters:
<light_blue>bool $a</light_blue>
<light_blue>string $b</light_blue> = \'foo\'' . PHP_EOL)
        );
    }
}
 