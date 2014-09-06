<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 13:21
 */

namespace Bonefish\Viewhelper\Builder;


use Bonefish\Viewhelper\Navigation\Navigation;
use Bonefish\Viewhelper\Navigation\NavigationElement;
use Bonefish\Viewhelper\Navigation\NavigationSubmenu;

class NavigationBuilder extends Builder
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var \Respect\Relational\Mapper
     * @inject eagerly
     */
    public $mapper;

    /**
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function build()
    {
        $navigation = new Navigation();

        $navigationElements = $this->mapper->navigationelement->navigation_navigationelement->navigation[$this->getId()]->fetchAll();
        $elements = array();

        foreach($navigationElements as $element)
        {
            $children = $this->mapper->navigationsubmenu->navigationelement_navigationsubmenu->navigationelement[$element->id]->fetchAll();
            $submenus = array();
            foreach($children as $child)
            {
                $submenus[] = new NavigationSubmenu($child->link, $child->label);
            }
            $elements[] = new NavigationElement($element->link, $element->label,$submenus);
        }

        $navigation->addManyChildren($elements);
        return $navigation->render();
    }
} 