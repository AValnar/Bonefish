<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.08.14
 * Time: 22:36
 */

namespace Bonefish\CRUD;

use Bonefish\Model\Model;

abstract class Basic
{

    protected $exclude = array();

    protected $add = array();

    /**
     * @var Model
     */
    protected $object;

    /**
     * @var \Bonefish\Database\MySqlIWrapper
     * @inject
     */
    protected $db;

    public function __construct(Model $object, $excluded = array(), $added = array())
    {
        $this->exclude = $excluded;
        $this->add = $added;
        $this->object = $object;
    }

    /**
     * @return \Bonefish\Model\Model
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return array
     */
    public function getAdd()
    {
        return $this->add;
    }

    /**
     * @return array
     */
    public function getExclude()
    {
        return $this->exclude;
    }

    /**
     * @return bool
     */
    abstract public function execute();

}