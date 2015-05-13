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

namespace Bonefish\DI;


use Nette\Reflection\AnnotationsParser;
use Nette\Reflection\ClassType;

class ClassNameResolver
{
    /**
     * @var ReflectionService
     */
    public $reflectionService;

    /**
     * @var array
     */
    protected $interfaceImplementations = array();

    /**
     * @param string $className
     * @param ClassType $foundInClassReflection
     * @return string
     */
    public function resolveClassName($className, ClassType $foundInClassReflection = NULL)
    {
        if ($foundInClassReflection === NULL) {
            $foundInClassReflection = $this->reflectionService->getReflection($className);
        }

        $className = '\\' . AnnotationsParser::expandClassName($className, $foundInClassReflection);

        $r = $this->reflectionService->getReflection($className);

        if ($r->isInterface()) {
            $implementation = $this->getInterfaceImplementation($className);
            if ($implementation === NULL) {
                throw new \RuntimeException('Tried to inject interface without setting an implementation!');
            }
            $className = $implementation;
        }

        return $className;
    }

    /**
     * Get an implementation to use for an interface or NULL if not set
     *
     * @param string $interface
     * @return string|NULL
     */
    public function getInterfaceImplementation($interface)
    {
        if (isset($this->interfaceImplementations[$interface])) {
            return $this->interfaceImplementations[$interface];
        }

        return NULL;
    }
} 