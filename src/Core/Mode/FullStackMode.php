<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 08:53
 */

namespace Bonefish\Core\Mode;


class FullStackMode extends ACLMode
{
    /**
     * @var AutoloaderMode
     * @inject
     */
    public $autoLoaderMode;

    /**
     * @var TracyMode
     * @inject
     */
    public $tracyMode;

    /**
     * @var LatteMode
     * @inject
     */
    public $latteMode;

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        $initModes = array('autoLoaderMode', 'tracyMode', 'latteMode');

        foreach($initModes as $mode)
        {
            $this->{$mode}->setParameters($this->getParameters());
            $this->{$mode}->init();
            $this->setParameters($this->{$mode}->getParameters());
        }
    }

} 