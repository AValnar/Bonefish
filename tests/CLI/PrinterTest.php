<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 21.09.14
 * Time: 11:05
 */

namespace Bonefish\Tests\CLI;


use Bonefish\CLI\Printer;

class PrinterTest extends \PHPUnit_Framework_TestCase {

    public function testPrettyMethod()
    {
        $this->expectOutputString('[92m[0m
[mprettyMethod[0m
[m[0m
[mMethod Parameters:[0m
[m[94mmixed $object[0m[m[0m
[m[94mstring $method[0m[m[0m
');
        $printer = new Printer();
        $printer->prettyMethod($printer,'prettyMethod');
    }

    public function testPrettyMethodWithDefault()
    {
        $this->expectOutputString('[92mGreet someone[0m
[mgreetCommand[0m
[m[0m
[mMethod Parameters:[0m
[m[94mstring $name[0m[m = \'Joe\'[0m
');
        $autoloader = new \Bonefish\Autoloader\Autoloader();
        $autoloader->addNamespace('Bonefish\HelloWorld','Packages/Bonefish/HelloWorld');
        $autoloader->register();

        $object = new \Bonefish\HelloWorld\Controller\Command();

        $printer = new Printer();
        $printer->prettyMethod($object,'greetCommand');
    }
}
 