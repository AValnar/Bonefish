<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 22.04.2015
 * Time: 09:53
 */
namespace Bonefish\View;

use Bonefish\Viewhelper\AbstractViewhelper;


/**
 * Copyright (C) 2014  Alexander Schmidt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-09-04
 * @package Bonefish\View
 */
interface IView
{
    /**
     * @param string $name
     * @param mixed $value
     */
    public function assign($name, $value);

    public function render($output = TRUE);

    /**
     * @param string $layout
     * @return self
     */
    public function setLayout($layout);

    /**
     * @return string
     */
    public function getLayout();

    /**
     * @param AbstractViewhelper $helper
     */
    public function addMacro(AbstractViewhelper $helper);
}