<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.09.14
 * Time: 14:29
 */

namespace Bonefish\Viewhelper\Typography;


use Bonefish\View\ContentElement;

class Text extends ContentElement
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    public function render()
    {
        return $this->content;
    }
} 