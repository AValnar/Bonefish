<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.08.14
 * Time: 22:09
 */

namespace Bonefish\Repository;

use Bonefish\Model\Model;

abstract class Repository
{
    protected $tableName;

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