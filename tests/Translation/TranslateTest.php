<?php
/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-05-06
 * @package    Bonefish
 * @subpackage Tests\Helper
 */

namespace Bonefish\Tests\Translation;


use Bonefish\Translation\Translate;

class TranslateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Translate
     */
    protected $translator;

    public function setUp()
    {
        $this->translator = new Translate();
    }

    /**
     * @dataProvider providerGetterAndSetter
     */
    public function testGetterAndSetter($expected, $given, $setter, $getter)
    {
        if ($setter != false) {
            $this->translator->{$setter}($given);
        }
        $this->assertEquals($expected, $this->translator->{$getter}());
    }

    /**
     * @depends testGetterAndSetter
     */
    public function testExpandLanguage()
    {
        $language['en']['unitTest'] = 'foo';
        $test['en']['unitTest'] = 'foo';
        $this->translator->expandLanguages($test);

        $this->assertEquals($language, $this->translator->getLanguages());
    }

    /**
     * @depends testGetterAndSetter
     */
    public function testTranslateReturnEmpty()
    {
        $this->assertEquals('', $this->translator->translate(''));
    }

    private function translate($expected, $key)
    {
        $this->translator->setSafeMode(true);
        $this->assertEquals($expected, $this->translator->translate($key));
        $this->translator->setSafeMode(false);
        $this->assertEquals($expected, $this->translator->translate($key));
    }

    /**
     * @depends testGetterAndSetter
     */
    public function testTranslateReturnKey()
    {
        $this->translate('phpunittestkey', 'phpunittestkey');
    }

    /**
     * @depends testGetterAndSetter
     */
    public function testTranslateReturnTranslation()
    {
        $test['en']['phpunittestTranslation'] = 'foobar';
        $this->translator->expandLanguages($test);
        $this->translate('foobar', 'phpunittestTranslation');
    }

    /**
     * @depends testGetterAndSetter
     */
    public function testTranslateReturnTranslationSafeMode()
    {
        $test['en']['phpunittestTranslation'] = 'test';
        $this->translator->expandLanguages($test);
        $test['en']['phpunittestTranslation'] = 'foobar';
        $this->translator->expandLanguages($test);
        $this->translator->setSafeMode(true);
        $this->assertEquals('foobar', $this->translator->translate('phpunittestTranslation'));
    }

    /**
     * @depends testGetterAndSetter
     */
    public function testTranslateFallback()
    {
        $test['en']['phpunittestTranslation'] = 'foobar';
        $this->translator->expandLanguages($test);
        $this->translator->setFallback('en');
        $this->translator->setDefault('de');
        $this->translate('foobar', 'phpunittestTranslation');
    }

    /**
     * @depends testGetterAndSetter
     * @depends testTranslateReturnTranslationSafeMode
     */
    public function testTranslateFallbackTranslationSafeMode()
    {
        $test['en']['phpunittestTranslation'] = 'test';
        $this->translator->expandLanguages($test);
        $test['en']['phpunittestTranslation'] = 'foobar';
        $this->translator->expandLanguages($test);
        $this->translator->setSafeMode(true);
        $this->translator->setFallback('en');
        $this->translator->setDefault('de');
        $this->assertEquals('foobar', $this->translator->translate('phpunittestTranslation'));
    }

    public function providerGetterAndSetter()
    {
        return array(
            array('en', 'en', 'setFallback', 'getFallback'),
            array('de', 'de', 'setFallback', 'getFallback'),
            array('en', 'en', 'setDefault', 'getDefault'),
            array('de', 'de', 'setDefault', 'getDefault'),
            array(true, true, 'setSafeMode', 'getSafeMode'),
            array(false, false, 'setSafeMode', 'getSafeMode'),
            array(array(), array(), false, 'getLanguages'),
        );
    }

}
 