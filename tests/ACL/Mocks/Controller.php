<?php

namespace Bonefish\Tests\ACL\Mocks;

/**
 * Class Controller
 * @package Bonefish\Tests\ACL\Mocks
 * @private
 */
class Controller extends \Bonefish\Controller\Base
{
    public function openMethod()
    {

    }

    /**
     * @private
     */
    public function privateMethod()
    {

    }

    /**
     * @exclude(Bonefish\Tests\ACL\Mocks\ProfileOne)
     */
    public function excludeMethod()
    {

    }

    /**
     * @private
     * @allow(Bonefish\Tests\ACL\Mocks\ProfileOne)
     */
    public function includeMethod()
    {

    }

    /**
     * @allow(Bonefish\Tests\ACL\Mocks\ProfileOne)
     */
    public function includeMethodTwo()
    {

    }

    /**
     * @exclude(Bonefish\Tests\ACL\Mocks\ProfileTwo)
     * @allow(Bonefish\Tests\ACL\Mocks\ProfileOne)
     */
    public function mixedMethod()
    {

    }
} 