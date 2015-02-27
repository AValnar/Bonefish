<?php

namespace Bonefish\CLI;

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
 * @date       2014-10-01
 * @package Bonefish\CLI
 */
interface ICLI
{

    /**
     * @param array $arguments
     */
    public function setParameters(array $arguments);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * Main handler
     */
    public function run();

    /**
     * Display all commands available in a package
     *
     * @param array<\Bonefish\Core\Package> $packages
     */
    public function help(array $packages);

    /**
     * Execute an action
     *
     * @param \Bonefish\Core\Package $package
     * @param string $action
     * @param array $parameters
     */
    public function execute($package, $action, $parameters = array());

    /**
     * Explain an action
     *
     * @param \Bonefish\Core\Package $package
     * @param string $action
     */
    public function explain($package, $action);
} 