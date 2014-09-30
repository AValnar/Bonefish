<?php

namespace Bonefish\Viewhelper;

/**
 * Copyright (C) 2014  Alexander Schmidt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-09-24
 * @package Bonefish\Viewhelper
 */
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

    /**
     * @param \Latte\MacroNode $node
     * @param \Latte\PhpWriter $writer
     * @return string
     */
    abstract public function getStart(\Latte\MacroNode $node, \Latte\PhpWriter $writer);

    /**
     * @param \Latte\MacroNode $node
     * @param \Latte\PhpWriter $writer
     * @return string
     */
    function getEnd(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        return $writer->write('');
    }

} 