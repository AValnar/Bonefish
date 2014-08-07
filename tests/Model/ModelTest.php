<?php
/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-05-02
 * @package    Bonefish
 * @subpackage Tests\Model
 */

namespace Bonefish\Tests\Model;

use Bonefish\Model\Model;
use Bonefish\Repository\Repository;

class ModelMock extends Model
{
    protected function postSave(){}

    protected function preSave(){}
}

class ModelTest extends \PHPUnit_Framework_TestCase
{

    public function testNew()
    {
        $repository = $this->getMockForAbstractClass('\Bonefish\Repository\Repository');

        $model = new ModelMock($repository);

        $this->assertEquals(true,$model->getIsNew());

        $model = new ModelMock($repository,false);

        $this->assertEquals(false,$model->getIsNew());
    }

    public function testValidate()
    {
        $repository = $this->getMockForAbstractClass('\Bonefish\Repository\Repository');

        $model = new ModelMock($repository);

        $this->assertEquals(true,$model->validate());
    }

    public function testRepository()
    {
        $repository = $this->getMockForAbstractClass('\Bonefish\Repository\Repository');

        $model = new ModelMock($repository);

        $this->assertEquals($repository,$model->getRepository());
    }

    public function testSave()
    {
        $repository = $this->getMockForAbstractClass('\Bonefish\Repository\Repository');

        $model = new ModelMock($repository);

        $repository->expects($this->once())
            ->method('save')
            ->with($model);

        $model->save();
    }
}
 