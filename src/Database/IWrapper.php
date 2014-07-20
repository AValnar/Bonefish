<?php

/**
 * Bonefish database wrapper interface
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

interface IWrapper
{
    public function error();

    public function query($sql);

    public function rollback();

    public function commit();

    public function autocommit($mode);

    public function escape($waste);

    public function affectedRows();

    public function fetch($sql, $class = '', $param = array());
}