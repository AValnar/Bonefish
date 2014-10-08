<?php

namespace Bonefish\Viewhelper\Core;

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
 * @date       2014-10-05
 * @package Bonefish\Viewhelper
 */
class Resource extends AbstractViewhelper
{
    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    public function __construct()
    {
        $this->setName('bonefish.resource');
        $this->setHasEnd(false);
    }

    /**
     * @param \Latte\MacroNode $node
     * @param \Latte\PhpWriter $writer
     * @return string
     */
    public function getStart(\Latte\MacroNode $node, \Latte\PhpWriter $writer)
    {
        $args = explode(',',$node->args);
        $package = $this->environment->createPackage($args[0],$args[1]);
        return $writer->write('echo \'' . $package->getPackageUrlPath() . '\'');
    }
} 