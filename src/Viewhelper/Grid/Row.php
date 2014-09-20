<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 19:01
 */

namespace Bonefish\Viewhelper\Grid;


use Bonefish\View\ContentElement;

class Row extends ContentElement
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $class
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    public function render()
    {
        return '<div class="row'.($this->class != '' ? ' '.$this->class.'' : '').'">' . $this->renderChildren() . '</div>';
    }
} 