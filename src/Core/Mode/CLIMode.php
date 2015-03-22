<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 08:53
 */

namespace Bonefish\Core\Mode;


class CLIMode extends NetteCacheMode
{
    /**
     * @var AutoloaderMode
     * @inject
     */
    public $autoLoaderMode;

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        $this->autoLoaderMode->setParameters($this->getParameters());
        $this->autoLoaderMode->init();
        $this->setParameters($this->autoLoaderMode->getParameters());
    }

} 