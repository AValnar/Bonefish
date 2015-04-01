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
 * @date       2014-09-07
 * @package Bonefish\ORM
 */
abstract class Repository extends \YetORM\Repository
{

    /**
     * @var \Nette\Database\Context
     * @Bonefish\Inject eagerly
     */
    public $context;

    /**
     * @var IContainer
     * @Bonefish\Inject
     */
    public $container;

    public function __construct()
    {

    }

    public function __init()
    {
        // Due to automatic di we don't have the context in the constructor thus we call it here once we have the context
        parent::__construct($this->context);
    }

    /**
     * @param Model $model
     */
    public function save(Model $model)
    {
        $this->persist($model);
    }

    public function createEntity($row = NULL)
    {
        $model = parent::createEntity($row);
        return $this->container->finalizeObject($model);
    }

    /**
     * @param  \Nette\Database\Table\Selection $selection
     * @param  string|callable $entity
     * @param  string $refTable
     * @param  string $refColumn
     * @return EntityCollection
     */
    protected function createCollection($selection, $entity = NULL, $refTable = NULL, $refColumn = NULL)
    {
        return $this->container->create(
            '\Bonefish\ORM\EntityCollection',
            array(
                $selection,
                $entity === NULL ? $this->getEntityClass() : $entity,
                $refTable,
                $refColumn
            )
        );
    }
} 