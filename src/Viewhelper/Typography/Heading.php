<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.09.14
 * Time: 14:25
 */

namespace Bonefish\Viewhelper\Typography;


use Bonefish\View\ContentElement;

class Heading extends ContentElement
{
    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param int $size
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

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
        return '<h'.$this->size.($this->class != '' ? ' class="'.$this->class.'"' : '').'>' . $this->renderChildren() . '</h'.$this->size.'>';
    }
} 