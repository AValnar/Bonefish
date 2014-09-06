<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 13:05
 */

namespace Bonefish\Viewhelper\Navigation;


use Bonefish\View\ContentElement;

class NavigationElement extends ContentElement
{
    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $label;

    /**
     * @param string $link
     * @param string $label
     * @param array $children
     */
    public function __construct($link = '', $label = '', $children = array())
    {
        $this->setLabel($label);
        $this->setLink($link);
        $this->addManyChildren($children);
    }

    /**
     * @param string $label
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $link
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    public function render()
    {
        if ($this->hasChildren()) {
            $html = $this->renderDropdown();
        } else {
            $html = $this->renderDefault();
        }
        return $html;
    }

    protected function renderDropdown()
    {
        return '<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">' . $this->getLabel() . ' &nbsp; <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu dropdown-menu-left  multi-level animated fast" data-effect="fadeInDown">
                                ' . $this->renderChildren() . '
                            </ul>
                        </li>';
    }

    protected function renderDefault()
    {
        return '<li><a href="' . $this->getLink() . '">' . $this->getLabel() . '</a></li>';
    }
} 