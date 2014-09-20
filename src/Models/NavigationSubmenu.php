<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.09.14
 * Time: 12:47
 */

namespace Bonefish\Models;


use Bonefish\ORM\Model;

/**
 * @property-read int $id
 * @property string $link
 * @property string $label
 */
class NavigationSubmenu extends Model
{
    /**
     * @internal
     * @return \Bonefish\Viewhelper\Navigation\NavigationSubmenu
     */
    public function getViewhelper()
    {
        return new \Bonefish\Viewhelper\Navigation\NavigationSubmenu($this->link,$this->label);
    }
} 