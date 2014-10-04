<?php

namespace Bonefish\ACL;

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
 * @date       2014-10-04
 * @package Bonefish\ACL
 */
class ACL implements IACL
{
    /**
     * @var \Bonefish\ACL\Profile
     */
    protected $profile;

    /**
     * @param \Bonefish\Controller\Base $controller
     * @param bool $action
     * @return bool
     */
    public function isPrivate(\Bonefish\Controller\Base $controller, $action = false)
    {
        $r = $this->getReflection($controller, $action);
        return $r->hasAnnotation('private');
    }

    /**
     * @param \Bonefish\Controller\Base $controller
     * @param bool $action
     * @return bool
     */
    public function isAllowed(\Bonefish\Controller\Base $controller, $action = false)
    {
        if (!$this->isPrivate($controller, $action)) {
            return true;
        }
        $r = $this->getReflection($controller,$action);
        if ($this->isExcluded($r)) {
            return false;
        }
        return $this->isIncluded($r);
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     */
    public function setProfile(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @param \Nette\Reflection\ClassType|\Nette\Reflection\Method $r
     * @return bool
     */
    protected function isIncluded($r)
    {
        return $this->checkAnnotation($r,'allow');
    }

    /**
     * @param \Nette\Reflection\ClassType|\Nette\Reflection\Method $r
     * @return bool
     */
    protected function isExcluded($r)
    {
        return $this->checkAnnotation($r,'exclude');
    }

    /**
     * @param \Nette\Reflection\ClassType|\Nette\Reflection\Method $r
     * @param string $annotation
     * @return bool
     */
    protected function checkAnnotation($r,$annotation)
    {
        if ($r->hasAnnotation($annotation)) {
            $dataSet = $r->getAnnotation($annotation);
            return in_array(get_class($this->profile), $dataSet);
        }
        return false;
    }

    /**
     * @param \Bonefish\Controller\Base $controller
     * @param string|bool $action
     * @return \Nette\Reflection\ClassType|\Nette\Reflection\Method
     */
    protected function getReflection($controller, $action)
    {
        if ($action === false) {
            return \Nette\Reflection\ClassType::from($controller);
        }
        return \Nette\Reflection\Method::from($controller, $action);
    }

} 