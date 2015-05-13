<?php
/**
 * Copyright (C) 2015  Alexander Schmidt
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
 * @copyright  Copyright (c) 2015, Alexander Schmidt
 * @date       13.05.2015
 */

namespace Bonefish\Reflection\Meta;


use Bonefish\Reflection\Traits\AnnotatedDocComment;

class ClassMeta
{
    use AnnotatedDocComment;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var PropertyMeta[]
     */
    protected $properties;

    /**
     * @var UseMeta[]
     */
    protected $useStatements;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

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
     * @return PropertyMeta[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param PropertyMeta[] $properties
     * @return self
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param PropertyMeta $property
     * @return self
     */
    public function addProperty(PropertyMeta $property)
    {
        $this->properties[] = $property;
        return $this;
    }

    /**
     * @return UseMeta[]
     */
    public function getUseStatements()
    {
        return $this->useStatements;
    }

    /**
     * @param UseMeta[] $useStatements
     * @return self
     */
    public function setUseStatements($useStatements)
    {
        $this->useStatements = $useStatements;
        return $this;
    }

    /**
     * @param UseMeta $useStatement
     * @return self
     */
    public function addUseStatement($useStatement)
    {
        $this->useStatements[] = $useStatement;
        return $this;
    }

} 