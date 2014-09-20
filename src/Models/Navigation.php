<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.09.14
 * Time: 12:51
 */

namespace Bonefish\Models;


use Bonefish\ORM\Model;

/**
 * @property-read int $id
 * @property string $name
 */
class Navigation extends Model
{
    /**
     * @return \YetORM\EntityCollection
     */
    public function getElements()
    {
        $selection = $this->record->related('navigation_navigationelement');
        return new \YetORM\EntityCollection($selection, '\Bonefish\Models\NavigationElement', 'navigationelement');
    }

    /**
     * @internal
     * @return \Bonefish\Viewhelper\Navigation\Navigation
     */
    public function getViewhelper()
    {
        return new \Bonefish\Viewhelper\Navigation\Navigation();
    }
} 