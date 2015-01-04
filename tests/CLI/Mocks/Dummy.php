<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 05.11.2014
 * Time: 10:21
 */

namespace Bonefish\Tests\CLI\Mocks;


class Dummy {

    public function noParameterNoDoc()
    {

    }

    /**
     * noParameterWithDoc comment
     */
    public function noParameterWithDoc()
    {

    }

    public function parameterNoDoc($a)
    {

    }

    /**
     * parameterWithDoc comment
     *
     * @param $a
     */
    public function parameterWithDoc($a)
    {

    }

    public function parameterOptionalNoDoc($a = TRUE)
    {

    }

    /**
     * parameterWithDoc comment
     *
     * @param bool $a
     */
    public function parameterOptionalWithDoc($a = TRUE)
    {

    }

    /**
     * mixedExample comment
     *
     * @param bool $a
     * @param string $b
     */
    public function mixedExample($a, $b = 'foo')
    {

    }
} 