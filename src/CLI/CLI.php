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
 * @date       2014-08-28
 * @package Bonefish\CLI
 * @method mixed border(string $char = '', integer $length = '')
 */
class CLI extends \JoeTannenbaum\CLImate\CLImate
{

    /**
     * @var array
     */
    protected $args;

    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string
     */
    protected $package;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @param array $args
     */
    public function __construct(array $args)
    {
        parent::__construct();
        $this->args = $args;
        $this->vendor = $this->setArgIfIsset(1);
        $this->package = $this->setArgIfIsset(2);
        $this->action = $this->setArgIfIsset(3);
    }

    /**
     * CLI handler
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

    protected function listAllCommands()
    {
        $packages = $this->environment->getAllPackages();

        $this->out('The following commands are present in your system:');

        /** \Bonefish\Core\Package $package */
        foreach ($packages as $package) {
            $this->loadPackageAndDisplayActions($package);
        }
    }

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

    protected function callAction()
    {
        list($obj, $action) = $this->checkIfActionExists();

        if (isset($this->args[4]) && $this->args[4] == 'help') {
            $printer = new Printer();
            $printer->prettyMethod($obj, $action);
        } else {
            call_user_func_array(array($obj, $action), $this->buildParameterList());
        }
    }

    /**
     * @return array
     */
    protected function buildParameterList()
    {
        $parameters = count($this->args) - 4;
        $params = array();
        for ($i = 0; $i < $parameters; $i++) {
            $params[] = $this->args[(4 + $i)];
        }
        return $params;
    }

    /**
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
     * @param \Bonefish\Core\Package $package
     */
    protected function loadPackageAndDisplayActions($package)
    {
        $this->out('<light_red>Vendor</light_red>: ' . $package->getVendor() . ' <light_red>Module</light_red>: ' . $package->getName());
        $this->border();
        $this->displayActionsFromPackage($package);
        $this->br();
    }

    /**
     * @param \Bonefish\Core\Package $package
     */
    protected function displayActionsFromPackage($package)
    {
        $controller = $package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
        $reflection = new \ReflectionClass($controller);

        $parser = new Parser();
        $actions = $parser->getSuffixMethods('Command',$reflection);

        foreach ($actions as $action) {
            $this->out($package->getVendor() . ' ' . $package->getName() . ' ' . $action);
        }
    }

    /**
     * @return \Bonefish\Controller\Command
     */
    protected function getCommandController()
    {
        $package = $this->environment->createPackage($this->vendor, $this->package);
        return $package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
    }

    protected function setArgIfIsset($key)
    {
        return isset($this->args[$key]) ? $this->args[$key] : '';
    }

} 