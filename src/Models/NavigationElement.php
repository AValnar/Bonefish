<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.09.14
 * Time: 12:41
 */

namespace Bonefish\Models;


use Bonefish\ORM\Model;

/**
 * @property-read int $id
 * @property string $link
 * @property string $label
 */
class NavigationElement extends Model
{
    /**
     * @return \YetORM\EntityCollection
     */
    public function getSubmenus()
    {
        $selection = $this->record->related('navigationelement_navigationsubmenu');
        return new \YetORM\EntityCollection($selection, '\Bonefish\Models\NavigationSubmenu', 'navigationsubmenu');
    }

    /**
     * @internal
     * @return \Bonefish\Viewhelper\Navigation\NavigationElement
     */
    public function getViewhelper()
    {
        return new \Bonefish\Viewhelper\Navigation\NavigationElement($this->link,$this->label);
    }
} 