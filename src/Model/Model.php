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

use Bonefish\Repository\Repository;

abstract class Model
{
    /**
     * @var bool
     */
    protected $isNew;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Enable fetch_object
     */
    public function __construct(Repository $repository,$new = true)
    {
        $this->isNew = $new;
        $this->repository = $repository;
    }

    /**
     * @return \Bonefish\Repository\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @void
     * @codeCoverageIgnore
     */
    abstract protected function preSave();

    /**
     * @void
     * @codeCoverageIgnore
     */
    abstract protected function postSave();

    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    public function save()
    {
        $this->repository->save($this);
    }
} 