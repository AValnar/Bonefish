<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 01.04.2015
 * Time: 18:05
 */

namespace Bonefish\Factory;


interface IFactory
{
    /**
     * Return an object with fully injected dependencies
     *
     * @param array $parameters
     * @return mixed
     */
    public function create(array $parameters = array());
}