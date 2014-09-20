<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 13:01
 */

namespace Bonefish\View;


abstract class ContentElement
{

    protected $children = array();

    public function addChild(ContentElement $child)
    {
        $this->children[] = $child;
    }

    public function addManyChildren(array $children)
    {
        /** @var ContentElement $child */
        foreach ($children as $child) {
            $this->children[] = $child;
        }
    }

    abstract public function render();

    public function renderChildren()
    {
        $html = '';
        /** @var ContentElement $child */
        foreach ($this->children as $child) {
            $html .= $child->render();
        }
        return $html;
    }

    public function hasChildren()
    {
        return !empty($this->children);
    }

    public function setParameters($parameters)
    {
        if ($parameters == '') {
            return;
        }

        $parameters = unserialize($parameters);

        foreach($parameters as $parameter => $value)
        {
            $this->{$parameter} = $value;
        }
    }
} 