<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 09:07
 */

namespace Bonefish\Core\Mode;


class LatteMode extends NetteCacheMode
{
    const MODE = 'LatteMode';

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        if ($this->isModeStarted(self::MODE)) return;

        if ($this->basicConfiguration === NULL)
        {
            $this->basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');
        }

        /** @var \Latte\Engine $latte */
        $latte = $this->container->get('\Latte\Engine');
        $path = $this->environment->getFullCachePath() . $this->basicConfiguration['global']['lattePath'];
        $this->createDir($path);
        $latte->setTempDirectory($path);

        $this->setModeStarted(self::MODE);
    }
} 