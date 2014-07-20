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
 * @subpackage Tests\Database
 */

namespace Bonefish\Tests\Database;

use Bonefish\Database\MySqlIWrapper;

class MySqlIWrapperMock extends MySqlIWrapper
{

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
}

class ResultMock
{
    /**
     * @var int
     */
    public $num_rows;

    public function fetch_assoc()
    {
        return null;
    }

    public function fetch_object($class = '', $parameter = array())
    {
        return null;
    }
}

class MySqlIWrapperTest extends \PHPUnit_Framework_TestCase
{
    protected $connection;

    protected $result;

    public function setUp()
    {
        $this->result = $this->getMockBuilder('\Bonefish\Tests\Database\ResultMock')
            ->disableOriginalConstructor()
            ->setMethods(array('fetch_assoc','fetch_object'))
            ->getMock();

        $this->connection = $this->getMockBuilder('\mysqli')
            ->disableOriginalConstructor()
            ->setMethods(array('query'))
            ->getMock();
    }

    public function testHasResultReturnFalseOnError()
    {
        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue(false));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals(false, $wrapper->hasResult('test'));
    }

    public function testHasResultReturnFalseOnNoResults()
    {
        $this->result->num_rows = 0;

        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->result));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals(false, $wrapper->hasResult('test'));
    }

    public function testHasResultReturnTrueOnResults()
    {
        $this->result->num_rows = 2;

        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->result));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals(true, $wrapper->hasResult('test'));
    }

    public function testFetchReturnFalseOnError()
    {
        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue(false));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals(false, $wrapper->fetch('test'));
    }

    public function testFetchReturnArrayOnEmptyResult()
    {
        $this->result->num_rows = 0;

        $this->result->expects($this->any())
            ->method('fetch_assoc')
            ->will($this->returnValue(FALSE));

        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->result));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals(array(), $wrapper->fetch('test'));
    }

    public function testFetchReturnResultOnOneResultAssoc()
    {
        $this->result->num_rows = 1;

        $this->result->expects($this->any())
            ->method('fetch_assoc')
            ->will($this->returnValue('foo'));

        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->result));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals('foo', $wrapper->fetch('test'));
    }

    public function testFetchReturnResultOnOneResultObject()
    {
        $this->result->num_rows = 1;

        $this->result->expects($this->any())
            ->method('fetch_object')
            ->will($this->returnValue('bar'));

        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->result));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals('bar', $wrapper->fetch('test','test'));
    }

    public function testFetchReturnArrayAssoc()
    {
        $i = 3;

        $this->result->num_rows = $i;

        for($a = 0;$a < $i;++$a) {
            $this->result->expects($this->at($a))
                ->method('fetch_assoc')
                ->will($this->returnValue('bar'));
        }

        $this->result->expects($this->at($i))
            ->method('fetch_assoc')
            ->will($this->returnValue(FALSE));

        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->result));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals(array('bar','bar','bar'), $wrapper->fetch('test'));
    }

    public function testFetchReturnArrayObject()
    {
        $i = 3;

        $this->result->num_rows = $i;

        for($a = 0;$a < $i;++$a) {
            $this->result->expects($this->at($a))
                ->method('fetch_object')
                ->will($this->returnValue('baz'));
        }

        $this->result->expects($this->at($i))
            ->method('fetch_object')
            ->will($this->returnValue(FALSE));

        $this->connection->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->result));

        $wrapper = new MySqlIWrapperMock($this->connection);
        $this->assertEquals(array('baz','baz','baz'), $wrapper->fetch('test','test'));
    }
}
 