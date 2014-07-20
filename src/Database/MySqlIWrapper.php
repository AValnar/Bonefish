<?php

/**
 * Bonefish database wrapper class for mysqli
 *
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-05-06
 * @package    Bonefish
 * @subpackage Database
 */

namespace Bonefish\Database;

class MySqlIWrapper implements IWrapper
{

    /** @var \mysqli $connection */
    protected $connection;

    private $config = array();

    /**
     * @param array $config
     * @codeCoverageIgnore
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->getConnection();
    }

    /**
     * Establish an mysql connection
     * @codeCoverageIgnore
     */
    private function getConnection()
    {
        $this->connection = new \mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['database']);

        if ($this->connection->connect_error) {
            die('Connect Error (' . $this->connection->connect_errno . ') ' . $this->connection->connect_error);
        }
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function error()
    {
        return $this->connection->error;
    }

    /**
     * @param string $sql
     * @return bool|\mysqli_result
     * @codeCoverageIgnore
     */
    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    /**
     * @codeCoverageIgnore
     */
    public function rollback()
    {
        $this->connection->rollback();
    }

    /**
     * @codeCoverageIgnore
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * @param bool $mode
     * @codeCoverageIgnore
     */
    public function autocommit($mode)
    {
        $this->connection->autocommit($mode);
    }

    /**
     * @param string $waste
     * @return string
     * @codeCoverageIgnore
     */
    public function escape($waste)
    {
        return $this->connection->real_escape_string($waste);
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function affectedRows()
    {
        return $this->connection->affected_rows;
    }

    /**
     * function fetch
     *
     * Query a sql string and return false on error.
     * On success return single row or multi array with all found results
     *
     * Return mode is assoc if no class is specified
     *
     * @param string $sql
     * @param string $class
     * @param array $param
     * @return array|bool
     */

    public function fetch($sql, $class = '', $param = array())
    {
        $result = $this->query($sql);

        if (!$result) {
            return false;
        }

        if ($result->num_rows == 1) {
            if ($class == '') {
                return $result->fetch_assoc();
            }
            return $result->fetch_object($class, $param);
        }

        $ret = array();

        if ($class == '') {
            while ($row = $result->fetch_assoc()) {
                $ret[] = $row;
            }
        } else {
            while ($row = $result->fetch_object($class, $param)) {
                $ret[] = $row;
            }
        }

        return $ret;
    }

    /**
     * Run query and return true if a result was found , false if not or on error
     *
     * @param string $sql
     * @return bool
     */

    public function hasResult($sql)
    {
        $result = $this->query($sql);

        if (!$result) {
            return false;
        }

        return ($result->num_rows >= 1);
    }
} 