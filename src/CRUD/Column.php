<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 08.08.14
 * Time: 20:28
 */

namespace Bonefish\CRUD;


class Column
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @var bool
     */
    protected $function;

    public function __construct($name = '', $value = '', $default = '', $function = '')
    {
        $this->name = $name;
        $this->value = $value;
        $this->default = $default;
        $this->function = $function;
    }

    /**
     * @param mixed $default
     * @return self
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param boolean $function
     * @return self
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


} 