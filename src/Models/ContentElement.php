<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 09.09.14
 * Time: 21:11
 */

namespace Bonefish\Models;


use Bonefish\ORM\Model;

/**
 * @property-read int $id
 * @property string $type
 * @property string $content
 * @property int $parent
 */
class ContentElement extends Model
{
    /**
     * @return \YetORM\EntityCollection
     */
    public function getChildren()
    {
        $selection = $this->record->related('contentelement', 'parent');
        return new \YetORM\EntityCollection($selection, '\Bonefish\Models\ContentElement');
    }

    /**
     * @internal
     * @return mixed
     */
    public function getViewhelper()
    {
        /** @var \Bonefish\View\ContentElement $obj */
        $obj = new $this->type();
        $obj->setParameters($this->content);
        $children = $this->getChildren();

        /** @var \Bonefish\Models\ContentElement $child */
        foreach($children as $child) {
            $viewhelper = $child->getViewhelper();
            $obj->addChild($viewhelper);
        }

        return $obj;
    }
} 