<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.08.14
 * Time: 22:36
 */

namespace Bonefish\CRUD;

use Bonefish\Model\Model;

class Statement
{

    /**
     * @var array
     */
    protected $exclude = array();

    /**
     * @var Column[]
     */
    protected $add = array();

    /**
     * @var Model
     */
    protected $object;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $start;

    /**
     * @var array
     */
    protected $where;

    /**
     * @var \Bonefish\Database\MySqlIWrapper
     * @inject
     */
    protected $db;

    protected $type;

    protected $sql;

    const TYPE_READ = 'SELECT * FROM ';

    const TYPE_CREATE = 'INSERT INTO ';

    const TYPE_DELETE = 'DELETE FROM ';

    const TYPE_UPDATE = 'UPDATE ';

    public function __construct(Model $object, $excluded = array(), $added = array())
    {
        $this->exclude = $excluded;
        $this->add = $added;
        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param mixed $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $limit
     * @return self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $start
     * @return self
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param array $where
     * @return self
     */
    public function setWhere($where)
    {
        $this->where = $where;
        return $this;
    }

    /**
     * @return array
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @return \Bonefish\Model\Model
     */
    public function getObject()
    {
        return $this->object;
    }

    protected function createSql()
    {
        $model = $this->getObject();

        $columns = $this->getModelColumns($model);

        if ($model->getIsNew()) {
            $columns = $this->addAdditionalColumns($columns);
        }

        $repository = $model->getRepository();

        $sql = $this->type.$repository->getTableName();

        switch($this->type)
        {
            case self::TYPE_CREATE:
                $names = array();
                $values = array();
                /** @var Column $column*/
                foreach($columns as $column)
                {
                    $names[] = $column->getName();
                    $values[] = $column->getValue();
                }
                $sql .= ' (`'.implode('`,`',$names).'`) VALUES ("'.implode('","',$values).'")';
                break;
            case self::TYPE_UPDATE:
                $sql .= ' SET ';
                /** @var Column $column*/
                foreach($columns as $key => $column)
                {
                    $sql .= '`'.$column->getName().'` = '.$column->getValue();
                    end($columns);
                    if ($key !== key($columns)) {
                        $sql .= ',';
                    }
                }
                $sql .= ' WHERE '.$this->getWhere().$this->createLimitSql();
                break;
            case self::TYPE_DELETE:
            case self::TYPE_READ:
                $sql .= ' WHERE '.$this->getWhere().$this->createLimitSql();
                break;
            default:
                break;
        }

        return $sql;
    }

    protected function createLimitSql()
    {
        if ($this->getStart() != '' && $this->getLimit() != '') {
            return ' LIMIT '.$this->getStart().','.$this->getLimit();
        } elseif ($this->getLimit() != '') {
            return ' LIMIT '.$this->getLimit();
        }
        return '';
    }

    protected function addAdditionalColumns($columns)
    {
        $added = $this->getAdd();

        foreach ($added as $key => $value) {
            if (isset($coloumns[$key])) {
                throw new \InvalidArgumentException('Invalid CRUD Configuration. Duplicate Key: ' . $key);
            }
            $columns[$key] = $value;
        }
        return $columns;
    }

    protected function getModelColumns($model)
    {
        $columns = array();
        $r = new \ReflectionClass($model);

        $properties = $r->getProperties();

        foreach ($properties as $property) {
            if (!in_array($property->getName(), $this->getExclude())) {
                $property->setAccessible(true);
                $columns[$property->getName()] = new Column($property->getName(), '"'.$this->db->escape($property->getValue($model)).'"');
            }
        }

        return $columns;
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
    public function execute(){
        $sql = $this->createSql();
        $this->db->autocommit(false);
        if ($this->type === self::TYPE_READ) {
            $result = $this->db->fetch($sql,get_class($this->getObject()),array($this->getObject()->getRepository()));
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            $this->db->rollback();
        }
        return $result;
    }

}