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

class ModelMock extends Model
{
    public $db;

    public $validate;

    protected $ID = 2;

    public function setDB($db) {
        $this->db = $db;
    }

    public function setNew($bool) {
        $this->isNew = $bool;
    }

    public function setTableName($name) {
        $this->tableName = $name;
    }

    public function setUidName($name) {
        $this->uidName = $name;
    }

    public function setValidate($bool) {
        $this->validate = $bool;
    }

    protected function validate()
    {
        return $this->validate;
    }
}

class ModelTest extends \PHPUnit_Framework_TestCase
{

    protected $wrapper;

    /**
     * @var ModelMock
     */
    protected $model;

    public function setUp()
    {
        $this->model = new ModelMock();

        $this->wrapper = $this->getMockBuilder('\Bonefish\Database\MySqlIWrapper')
            ->disableOriginalConstructor()
            ->setMethods(array('autocommit', 'query', 'rollback', 'commit','escape'))
            ->getMock();

        $this->model->setDB($this->wrapper);
    }

    /**
     * @dataProvider providerGetterAndSetter
     */
    public function testGetterAndSetter($expected, $given, $setter, $getter)
    {
        if ($setter != false) {
            $this->model->{$setter}($given);
        }
        $this->assertEquals($expected, $this->model->{$getter}());
    }

    /**
     * @expectedException \Exception
     */
    public function testUpdateThrowsExceptionForKeyTableName() {
        $this->model->update('tableName','test');
    }

    /**
     * @expectedException \Exception
     */
    public function testUpdateThrowsExceptionForKeyUidName() {
        $this->model->update('uidName','test');
    }

    /**
     * @expectedException \Exception
     */
    public function testUpdateThrowsExceptionForKeyChangeSet() {
        $this->model->update('changeSet','test');
    }

    public function testUpdate() {
        $this->assertEquals(array(),$this->model->getChangeHistory());
        $this->model->update('test','test');
        $this->assertEquals(array('test'),$this->model->getChangeHistory());
        $this->assertEquals('test',$this->model->test);
    }

    /**
     * @depends testUpdate
     */
    public function testUpdateWithEscape() {

        $this->wrapper->expects($this->once())
            ->method('escape')
            ->will($this->returnValue('foo'));

        $this->assertEquals(array(),$this->model->getChangeHistory());
        $this->model->update('test','test',true);
        $this->assertEquals(array('test'),$this->model->getChangeHistory());
        $this->assertEquals('foo',$this->model->test);
    }

    /**
     * @depends testUpdate
     */
    public function testSaveWillCreateInsertSql() {

        $this->wrapper->expects($this->once())
            ->method('query')
            ->with('INSERT INTO `foo` (`bar`,`baz`,`test`) VALUES ("foo","bar","baz")')
            ->will($this->returnValue(true));

        $this->model->update('bar','foo');
        $this->model->update('baz','bar');
        $this->model->update('test','baz');
        $this->model->setTableName('foo');
        $this->model->setValidate(true);

        $this->assertEquals(true,$this->model->save());
    }

    /**
     * @depends testUpdate
     */
    public function testSaveWillCreateUpdateSql() {

        $this->wrapper->expects($this->once())
            ->method('query')
            ->with('UPDATE `foo` SET  `bar` = "foo", `baz` = "bar", `test` = "baz" WHERE ID = 2 LIMIT 1')
            ->will($this->returnValue(true));

        $this->model->update('bar','foo');
        $this->model->update('baz','bar');
        $this->model->update('test','baz');
        $this->model->setTableName('foo');
        $this->model->setValidate(true);
        $this->model->setNew(false);

        $this->assertEquals(true,$this->model->save());
    }

    /**
     * @depends testUpdate
     */
    public function testSaveReturnFalseOnEmptyChangeSet() {

        $this->assertEquals(false,$this->model->save());
    }

    /**
     * @depends testUpdate
     * @depends testSaveReturnFalseOnEmptyChangeSet
     */
    public function testSaveReturnFalseOnFailedValidation() {

        $this->model->update('bar','foo');
        $this->model->setValidate(false);
        $this->assertEquals(false,$this->model->save());
    }

    /**
     * @depends testSaveWillCreateUpdateSql
     */
    public function testSaveWillRollback() {

        $this->wrapper->expects($this->once())
            ->method('query')
            ->with('UPDATE `foo` SET  `bar` = "foo", `baz` = "bar", `test` = "baz" WHERE ID = 2 LIMIT 1')
            ->will($this->returnValue(false));

        $this->wrapper->expects($this->once())
            ->method('rollback')
            ->will($this->returnValue(true));

        $this->model->update('bar','foo');
        $this->model->update('baz','bar');
        $this->model->update('test','baz');
        $this->model->setTableName('foo');
        $this->model->setValidate(true);
        $this->model->setNew(false);

        $this->assertEquals(false,$this->model->save());
    }

    public function providerGetterAndSetter()
    {
        return array(
            array('test', 'test', 'setTableName', 'getTableName'),
            array('foo', 'foo', 'setUidName', 'getUidName'),
            array('foo', 'foo', 'setNew', 'getIsNew')
        );
    }
}
 