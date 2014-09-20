<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 19:01
 */

namespace Bonefish\Viewhelper\Grid;


use Bonefish\View\ContentElement;

class Container extends ContentElement
{
    public function render()
    {
        return '<div class="container">' . $this->renderChildren() . '</div>';
    }
} 