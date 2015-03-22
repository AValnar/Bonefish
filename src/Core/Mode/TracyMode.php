<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 09:17
 */

namespace Bonefish\Core\Mode;


class TracyMode extends LoadAliasMode
{
    const MODE = 'TracyMode';

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        if ($this->isModeStarted(self::MODE)) return;

        if ($this->basicConfiguration['global']['develoment']) {
            \Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT);
        } else {
            \Tracy\Debugger::enable(\Tracy\Debugger::PRODUCTION);
        }
        \Tracy\Debugger::$strictMode = TRUE;

        $this->setModeStarted(self::MODE);
    }
} 