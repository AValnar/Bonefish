<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 24.09.14
 * Time: 22:21
 */

namespace Bonefish\Viewhelper;


abstract class AbstractViewhelper
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $hasEnd;

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $hasEnd
     * @return self
     */
    public function setHasEnd($hasEnd)
    {
        $this->hasEnd = $hasEnd;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasEnd()
    {
        return $this->hasEnd;
    }

    public function getStart(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        return $writer->write('');
    }

    public function getEnd(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        return $writer->write('');
    }

} 