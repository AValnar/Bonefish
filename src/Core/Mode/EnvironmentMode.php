<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 09:02
 */

namespace Bonefish\Core\Mode;


class EnvironmentMode extends AbstractMode
{
    const MODE = 'EnvironmentMode';

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var array
     */
    protected $basicConfiguration = NULL;

    /**
     * Init needed framework stack
     */
    public function init()
    {
        if ($this->isModeStarted(self::MODE)) return;

        $parameters = $this->getParameters();

        /** @var \Bonefish\Core\Environment $environment */
        $this->environment->setBasePath($parameters['basePath'])
            ->setConfigurationPath('/Configuration');

        $this->basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');
        $this->environment->setPackagePath($this->basicConfiguration['global']['packagePath'])
            ->setCachePath($this->basicConfiguration['global']['cachePath'])
            ->setLogPath($this->basicConfiguration['global']['logPath']);

        $this->setModeStarted(self::MODE);
    }
} 