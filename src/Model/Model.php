<?php

/**
 * Abstract database model for bonefish
 *
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-05-02
 * @package    Bonefish
 * @subpackage Model
 */

namespace Bonefish\Model;

abstract class Model
{
    /**
     * @var \Bonefish\Database\MySqlIWrapper
     * @inject
     */
    protected $db;

    /**
     * @var array
     */
    protected $changeSet = array();

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $uidName = 'ID';

    /**
     * @var bool
     */
    protected $isNew;

    /**
     * Enable fetch_object
     */
    public function __construct($new = true)
    {
        $this->isNew = $new;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getUidName()
    {
        return $this->uidName;
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (empty($this->changeSet)) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $this->preSave();

        $this->db->autocommit(false);

        if (!$this->isNew) {
            $sql = 'UPDATE `' . $this->tableName . '` SET ';

            $updated = array();

            foreach ($this->changeSet as $key) {
                if (!isset($updated[$key])) {
                    $updated[$key] = TRUE;

                    $sql .= ' `' . $key . '` = "' . $this->{$key} . '",';
                }
            }

            $sql = substr($sql, 0, -1);

            $id = $this->uidName;

            $sql .= ' WHERE ' . $id . ' = ' . $this->{$id} . ' LIMIT 1';
        } else {

            $rows = array();
            $values = array();

            foreach ($this->changeSet as $key) {
                if (!isset($updated[$key])) {
                    $updated[$key] = TRUE;
                    $rows[] = '`'.$key.'`';
                    $values[] = '"'.$this->{$key}.'"';
                }
            }
            $sql = 'INSERT INTO `'.$this->tableName.'` ('.implode(',',$rows).') VALUES ('.implode(',',$values).')';
        }

        $result = $this->db->query($sql);

        if (!$result) {
            $this->db->rollback();
            $this->db->autocommit(TRUE);
            return false;
        } else {
            $this->db->commit();
        }

        $this->db->autocommit(TRUE);

        $this->postSave();

        return TRUE;
    }

    /**
     * Update an object property
     *
     * @param string $key
     * @param mixed $value
     * @param bool $escape
     * @throws \Exception
     */

    public function update($key, $value, $escape = false)
    {
        if ($key == 'tableName' || $key == 'uidName' || $key == 'changeSet') {
            throw new \Exception('You cannot update tableName,uidName or changeSet');
        }

        $this->changeSet[] = $key;

        if ($escape) {
            $value = $this->db->escape($value);
        }

        $this->{$key} = $value;
    }

    /**
     * @return array
     */
    public function getChangeHistory()
    {
        return $this->changeSet;
    }

    protected function preSave()
    {
    }

    protected function postSave()
    {
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    protected function validate()
    {
        return true;
    }
} 