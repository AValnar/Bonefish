<?php

namespace Bonefish\CLI;

/**
 * Copyright (C) 2015  Alexander Schmidt
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
 * @copyright  Copyright (c) 2015, Alexander Schmidt
 * @date       2015-06-04
 */
interface CLIInterface
{

    // Comamnd types
    const HELP_COMMAND = 'help';
    const LIST_COMMAND = 'list';
    const EXPLAIN_COMMAND = 'explain';
    const EXECUTE_COMMAND = 'execute';

    // success code
    const CLI_SUCCESS = 0;

    // Error codes
    const INVALID_PARAMETER_AMOUNT = 1;
    const INVALID_COMMAND_TYPE = 2;
    const INVALID_COMMAND_PARAMETERS = 3;
    const COMMAND_EXECUTE_FAILED = 666;

    /**
     * Method to accept $argv array and save the supplied arguments for the runtime
     *
     * @param array $arguments
     */
    public function setParameters(array $arguments = []);


    /**
     * Main handler which is called after all arguments have been passed.
     *
     * The CLI must be able to execute the following commands:
     *
     * - help
     * - list [<vendor>] [<package>]
     * - explain <vendor> <package> <command>
     * - execute <vendor> <package> <command> [argument]*
     *
     * The run function should quit with exit and zero on success and an error code on failure.
     */
    public function run();
} 