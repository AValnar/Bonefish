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

    protected function convertModel()
    {
        $coloumns = array();
        $model = $this->getObject();

        $r = new \ReflectionClass($model);

        $properties = $r->getProperties();

        foreach ($properties as $property) {
            if (!in_array($property->getName(), $this->getExclude())) {
                $property->setAccessible(true);
                $coloumns[$property->getName()] = $property->getValue($model);
            }
        }

        if ($model->getIsNew()) {
            $added = $this->getAdd();

            foreach ($added as $key => $value) {
                if (isset($coloumns[$key])) {
                    throw new \InvalidArgumentException('Invalid CRUD Configuration. Duplicate Key: ' . $key);
                }
                $coloumns[$key] = $value;
            }
        }

        return $coloumns;
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