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

namespace Bonefish\Reflection;


use Bonefish\Reflection\Meta\AnnotationMeta;
use Bonefish\Reflection\Meta\ClassMeta;
use Bonefish\Reflection\Meta\PropertyMeta;
use Bonefish\Reflection\Meta\UseMeta;
use Bonefish\Reflection\Traits\AnnotatedDocComment;
use Doctrine\Common\Cache\Cache;
use Nette\Reflection\AnnotationsParser;
use Nette\Reflection\ClassType;

class ReflectionService
{
    /**
     * @var array
     */
    protected $reflections = array();

    /**
     * @var array
     */
    protected $metaReflections = array();

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param Cache $cache
     * @return self
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @param string $className
     * @return ClassType
     */
    public function getClassReflection($className)
    {
        if (!isset($this->reflections[$className])) {
            $this->reflections[$className] = new ClassType($className);
        }

        return $this->reflections[$className];
    }

    /**
     * @param string $className
     * @return ClassMeta
     */
    public function getClassMetaReflection($className)
    {
        if (!isset($this->metaReflections[$className])) {
            $this->metaReflections[$className] = $this->buildClassMetaReflection($className);
        }

        return $this->metaReflections[$className];
    }

    /**
     * @param string $className
     * @return ClassMeta
     */
    protected function buildClassMetaReflection($className)
    {
        $classMeta = new ClassMeta();

        $reflection = $this->getClassReflection($className);

        $classMeta->setName($className);

        // Create Use Meta
        $parsedPHP = AnnotationsParser::parsePhp(file_get_contents($reflection->getFileName()));

        foreach ($parsedPHP[$reflection->getName()]['use'] as $alias => $class) {
            $useStatement = new UseMeta();
            $useStatement->setAlias($alias);
            $useStatement->setOriginal($class);
            $classMeta->addUseStatement($useStatement);
        }

        $classMeta->setDocComment($reflection->getDocComment());
        /** @var ClassMeta $classMeta */
        $classMeta = $this->createAnnotationMeta($reflection->getAnnotations(), $classMeta);

        unset($parsedPHP);

        // Create Property Meta

        foreach ($reflection->getProperties() as $propertyReflection) {
            $property = new PropertyMeta();
            $property->setName($propertyReflection->getName());
            $property->setDocComment($propertyReflection->getDocComment());
            /** @var PropertyMeta $property */
            $property = $this->createAnnotationMeta($propertyReflection->getAnnotations(), $property);
            $classMeta->addProperty($property);
        }

        return $classMeta;
    }

    /**
     * @param array $value
     * @return null|string
     */
    protected function getAnnotationProperties(array $value)
    {
        $value = $value[0];
        $parameter = null;

        if (is_string($value)) {
            $parameter = $value;
        } elseif (is_object($value) && $value instanceof \ArrayAccess) {
            foreach ($value as $key => $val) {
                $parameter[$key] = $val;
            }
        }

        return $parameter;
    }

    /**
     * @param array $annotations
     * @param ClassMeta|PropertyMeta $metaClass
     * @return AnnotatedDocComment
     */
    protected function createAnnotationMeta(array $annotations, $metaClass)
    {
        foreach ($annotations as $annotation => $value) {
            $annotationMeta = new AnnotationMeta();
            $annotationMeta->setName($annotation);
            $annotationMeta->setParameter($this->getAnnotationProperties($value));

            $metaClass->addAnnotation($annotationMeta);
        }

        return $metaClass;
    }
} 