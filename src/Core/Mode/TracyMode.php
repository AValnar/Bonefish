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
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        if ($this->isModeStarted(self::MODE)) return;

        $logPath = $this->environment->getFullLogPath();

        if ($this->basicConfiguration === NULL)
        {
            $this->basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');
        }

        if ($this->basicConfiguration['global']['develoment']) {
            \Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, $logPath);
        } else {
            \Tracy\Debugger::enable(\Tracy\Debugger::PRODUCTION, $logPath);
        }
        \Tracy\Debugger::$strictMode = TRUE;

        $this->setModeStarted(self::MODE);
    }
} 