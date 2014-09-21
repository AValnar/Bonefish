<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 21.09.14
 * Time: 10:59
 */

namespace Bonefish\Tests\CLI;


use Bonefish\CLI\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSuffixMethods()
    {
        $parser = new Parser();
        $reflection = new \ReflectionClass($parser);
        $return = $parser->getSuffixMethods('Methods',$reflection);
        $this->assertThat(count($return),$this->equalTo(3));
    }

    public function testGetPrefixMethods()
    {
        $parser = new Parser();
        $reflection = new \ReflectionClass($parser);
        $return = $parser->getPrefixMethods('get',$reflection);
        $this->assertThat(count($return),$this->equalTo(3));
    }
}
 