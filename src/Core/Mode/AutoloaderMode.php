<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 08:56
 */

namespace Bonefish\Core\Mode;


class AutoloaderMode extends AbstractMode
{
    const MODE = 'AutoloaderMode';

    /**
     * Init needed framework stack
     */
    public function init()
    {
        if ($this->isModeStarted(self::MODE)) return;

        /** @var \Bonefish\Autoloader\Autoloader $autoloader */
        $autoloader = $this->container->get('\Bonefish\Autoloader\Autoloader');
        $autoloader->register();

        $this->setModeStarted(self::MODE);
    }
} 