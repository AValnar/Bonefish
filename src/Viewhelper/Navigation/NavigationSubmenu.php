<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 13:05
 */

namespace Bonefish\Viewhelper\Navigation;


class NavigationSubmenu extends NavigationElement
{
    public function renderDropdown()
    {
        return '<li class="dropdown-submenu"> <a tabindex="-1" href="#">' . $this->getLabel() . ' &nbsp; <i class="fa fa-angle-right"></i></a>
                                    <ul class="dropdown-menu">
                                        ' . $this->renderChildren() . '
                                    </ul>
                                </li>';
    }
} 