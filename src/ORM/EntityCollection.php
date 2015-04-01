<?php

namespace Bonefish\ORM;
use Bonefish\DI\IContainer;

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
 * @date       2014-10-03
 * @package Bonefish\ORM
 */
class EntityCollection extends \YetORM\EntityCollection
{

    /** @var array */
    protected $keys;

    /**
     * @var IContainer
     * @inject
     */
    public $container;

    /** @return void */
    protected function loadData()
    {
        if ($this->data === NULL) {
            $factory = $this->createCallback();
            $this->data = array();
            foreach ($this->selection as $row) {
                $record = $this->refTable === NULL ? $row : $row->ref($this->refTable, $this->refColumn);
                $this->data[] = \Nette\Utils\Callback::invoke($factory, $record);
            }
        }
    }

    /**
     * @return \Closure
     */
    protected function createCallback()
    {
        if ($this->entity instanceof \Closure) {
            return $this->entity;
        }

        $class = $this->entity;
        $container = $this->container;
        return function ($record) use ($class, $container) {
            return $container->create($class, array($record));
        };
    }

    /** @return void */
    public function rewind()
    {
        $this->loadData();
        $this->keys = array_keys($this->data);
        reset($this->keys);
    }

    /** @return Model */
    public function current()
    {
        $key = current($this->keys);
        return $key === FALSE ? FALSE : $this->data[$key];
    }


    /** @return mixed */
    public function key()
    {
        return current($this->keys);
    }


    /** @return void */
    public function next()
    {
        next($this->keys);
    }


    /** @return bool */
    public function valid()
    {
        return current($this->keys) !== FALSE;
    }
} 