<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 09:14
 */

namespace Bonefish\Core\Mode;


class LoadAliasMode extends EnvironmentMode
{
    const MODE = 'LoadAliasMode';

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        if ($this->isModeStarted(self::MODE)) return;

        foreach ($this->basicConfiguration['alias'] as $class => $alias) {
            $this->container->alias($alias, $class);
        }

        $this->setModeStarted(self::MODE);
    }
} 