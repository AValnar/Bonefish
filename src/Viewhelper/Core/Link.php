<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 24.09.14
 * Time: 22:32
 */

namespace Bonefish\Viewhelper\Core;


use Bonefish\Viewhelper\AbstractViewhelper;

class Link extends AbstractViewhelper
{

    public function __construct()
    {
        $this->setName('bonefish.link');
        $this->setHasEnd(true);
    }

    public function getStart(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        return $writer->write('echo \'<a href="'.$node->args.'">\'');
    }

    public function getEnd(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        return $writer->write('echo \'</a>\'');
    }
} 