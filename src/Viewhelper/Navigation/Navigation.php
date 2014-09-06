<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 13:05
 */

namespace Bonefish\Viewhelper\Navigation;


use Bonefish\View\ContentElement;

class Navigation extends ContentElement
{
    public function render()
    {
        return '<div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav navbar-right hidden-xs" id="navigation">
                        ' . $this->renderChildren() . '
                        <li><button class="btn btn-circle btn-header-search" ><i class="fa fa-search"></i></button></li>
                    </ul>
                </div>';
    }
} 