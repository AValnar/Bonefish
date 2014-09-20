<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 19:05
 */

namespace Bonefish\Viewhelper\Grid;


use Bonefish\View\ContentElement;

class Column extends ContentElement
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @param int $width
     * @return self
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function render()
    {
        return '<div class="col-md-'.$this->width.'">' . $this->renderChildren() . '</div>';
    }
} 