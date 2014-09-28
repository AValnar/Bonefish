<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 24.09.14
 * Time: 22:32
 */

namespace Bonefish\Viewhelper\Core;


use Bonefish\Viewhelper\AbstractViewhelper;

class Base extends AbstractViewhelper
{
    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    public function __construct()
    {
        $this->setName('bonefish.base');
        $this->setHasEnd(false);
    }

    public function getStart(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        $config = $this->configurationManager->getConfiguration('Configuration.neon');
        return $writer->write('echo \'<base href="'.$config['global']['baseUrl'].'">\'');
    }
} 