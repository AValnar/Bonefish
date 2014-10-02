<?php

namespace Bonefish\CLI;

/**
 * This class provides the command line tool for the Bonefish framework.
 * Depending on the arguments supplied on the command line we will either
 * show a list of all packages in our system, a documentation for a
 * specific action we want to call or calling a specified action.
 *
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
 * @date       2014-08-28
 * @package Bonefish\CLI
 */
class CLI extends CLIHelper
{

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;


    /**
     * Main CLI handler. After a nice welcome message we show some help
     * or call an action
     */
    public function execute()
    {
        $this->lightGreen()->out('Welcome to Bonefish!')->br();

        if (strtolower($this->vendor) == 'help') {
            $this->listAllCommands();
        } else {
            if ($this->validateArgs()) {
                $this->executeCommand();
            }
        }
    }

    /**
     * Get all packages in our environment and display the actions present in those packages
     */
    protected function listAllCommands()
    {
        $packages = $this->environment->getAllPackages();

        $this->out('The following commands are present in your system:');

        /** \Bonefish\Core\Package $package */
        foreach ($packages as $package) {
            $this->loadPackageAndDisplayActions($package);
        }
    }

    /**
     * Execute a certain command.
     * If the first parameter is "help" display all available commands inside the set package.
     */
    protected function executeCommand()
    {
        if (strtolower($this->action) == 'help') {
            $this->loadPackageAndDisplayActions($this->environment->createPackage($this->vendor, $this->package));
            return;
        }

        if (!$this->checkIfActionExists()) {
            return;
        }

        $this->callAction();
    }

    /**
     * Call an existing action.
     * If the first parameter is "help" display a documentation for said action.
     */
    protected function callAction()
    {
        list($obj, $action) = $this->checkIfActionExists();

        if (isset($this->args[4]) && $this->args[4] == 'help') {
            $this->printer->prettyMethod($obj, $action);
        } else {
            call_user_func_array(array($obj, $action), $this->buildParameterList());
        }
    }

    /**
     * Check if an action exists and if so return the controller and action otherwise return false.
     *
     * @return array|bool
     */
    protected function checkIfActionExists()
    {
        $controller = $this->getCommandController();
        $action = $this->args[3] . 'Command';
        if (!is_callable(array($controller, $action))) {
            $this->out('Invalid action!');
            return false;
        }
        return array($controller, $action);
    }

    /**
     * Validate arguments. Check if arguments are set and if we can create a controller.
     *
     * @return bool
     */
    protected function validateArgs()
    {
        if (!isset($this->args[2]) || !isset($this->args[3])) {
            $this->out('Incomplete command!');
            return false;
        }

        try {
            $this->getCommandController();
        } catch (\Exception $e) {
            $this->out('Invalid command!');
            return false;
        }

        return true;
    }

    /**
     * Create a controller for the curretly selected package
     *
     * @return \Bonefish\Controller\Command
     */
    protected function getCommandController()
    {
        $package = $this->environment->createPackage($this->vendor, $this->package);
        return $package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
    }

} 