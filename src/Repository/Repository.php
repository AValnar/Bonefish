<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.08.14
 * Time: 22:09
 */

namespace Bonefish\Repository;

use Bonefish\Model\Model;
use Bonefish\CRUD;

abstract class Repository
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param Model $Model
     */
    abstract public function save(Model $Model);

    /**
     * @return Model
     */
    abstract public function create();

    /**
     * @param Model $Model
     * @return bool
     */
    abstract public function delete(Model $Model);

    /**
     * @return Model[]
     */
    abstract public function findAll();
} 