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
    protected $baseDir;

    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string|bool
     */
    protected $package;

    /**
     * @var string|bool
     */
    protected $action;

    const MODULE_PATH = '/modules';

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @param array $args
     * @param string $baseDir
     */
    public function __construct(array $args, $baseDir)
    {
        parent::__construct();
        $this->args = $args;
        $this->vendor = $args[1];
        $this->package = isset($args[2]) ? $args[2] : FALSE;
        $this->action = isset($args[3]) ? $args[3] : FALSE;
        $this->baseDir = $baseDir;
    }

    /**
     * CLI handler
     */
    public function execute()
    {
        $this->lightGreen()->out('Welcome to Bonefish!')->br();

        if (strtolower($this->vendor) == 'help') {
            $this->listCommands();
        } else {
            if ($this->validateVendorPackageArgs()) {
                $this->executeCommand();
            }
        }
    }

    /**
     * @param string $package
     */
    protected function listCommands($package = '')
    {
        $path = $this->getPathForCommandList($package);

        $this->out('The following commands are present in your system:');

        $vendors = $this->getVendorsOrPackagesFromPath($path);
        foreach ($vendors as $vendor) {
            $packages = $this->getVendorsOrPackagesFromPath($path . '/' . $vendor);
            foreach ($packages as $package) {
                $commandControllerPath = $this->getCommandControllerPath($vendor, $package);
                $this->parseCommandsFromFile($commandControllerPath);
            }
        }
    }

    /**
     * @param string $package
     * @return string
     */
    protected function getPathForCommandList($package)
    {
        $path = $this->baseDir . self::MODULE_PATH;

        if ($package != '') {
            $path .= $package;
        }

        return $path;
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getVendorsOrPackagesFromPath($path)
    {
        $return = array();
        $iterator = new \DirectoryIterator($path);
        foreach ($iterator as $element) {
            if (!$element->isDir() || $element->isDot()) continue;
            $return[] = $element->__toString();
        }
        return $return;
    }

    protected function executeCommand()
    {
        if ($this->action == 'help') {
            $this->listCommands('/' . $this->vendor . '/' . $this->package);
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
            $this->prettyPrint($obj, $action);
        } else {
            call_user_func_array(array($obj, $action), array($this->buildParameterList()));
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
        require_once $this->getCommandControllerPath();
        $name = $this->getCommandClassForVendorPackage($this->args[1], $this->args[2]);
        $obj = $this->container->create($name, array($this->baseDir));
        $action = $this->args[3] . 'Command';
        if (!is_callable(array($obj, $action))) {
            $this->out('Invalid action!');
            return false;
        }
        return array($obj, $action);
    }

    /**
     * @return bool
     */
    protected function validateVendorPackageArgs()
    {
        if (!isset($this->args[2]) || !isset($this->args[3])) {
            $this->out('Incomplete command!');
            return false;
        }

        // check if controller exists
        if (!file_exists($this->getCommandControllerPath())) {
            $this->out('Invalid command!');
            return false;
        }

        return true;
    }

    /**
     * @param string $path
     */
    protected function parseCommandsFromFile($path)
    {
        if (file_exists($path)) {
            return;
        }
        require_once $path;
        $vendor = $this->getVendorFromControllerFromPath($path);
        $package = $this->getPackageFromControllerFromPath($path);
        $r = new \ReflectionClass($this->getCommandClassForVendorPackage($vendor, $package));
        $this->out('<light_red>Vendor</light_red>: ' . $vendor . ' <light_red>Module</light_red>: ' . $package);
        $this->border();
        $this->getActionsFromController($r, $vendor, $package);
        $this->br();
    }

    /**
     * @param bool|string $vendor
     * @param bool|string $package
     * @return string
     */
    protected function getCommandControllerPath($vendor = FALSE, $package = FALSE)
    {
        if (!$vendor && !$package) {
            $vendor = $this->vendor;
            $package = $this->package;
        }

        return $this->baseDir . self::MODULE_PATH . '/' . $vendor . '/' . $package . '/Controller/Command.php';
    }

    /**
     * @param \ReflectionClass $reflection
     * @param string $vendor
     * @param string $package
     */
    protected function getActionsFromController(\ReflectionClass $reflection, $vendor, $package)
    {
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            preg_match('/([a-zA-Z]*)Command/', $method->getName(), $match);
            if (isset($match[1])) {
                $this->out($vendor . ' ' . $package . ' ' . $match[1]);
            }
        }
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getVendorFromControllerFromPath($path)
    {
        return $this->getParts($path)[1];
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getPackageFromControllerFromPath($path)
    {
        return $this->getParts($path)[2];
    }

    /**
     * @param string $path
     * @return array
     */
    private function getParts($path)
    {
        return explode('/', str_replace($this->baseDir . self::MODULE_PATH, '', $path));
    }

    /**
     * @param string $vendor
     * @param string $package
     * @return string
     */
    protected function getCommandClassForVendorPackage($vendor, $package)
    {
        return '\\' . $vendor . '\\' . $package . '\Controller\Command';
    }

    /**
     * @param mixed $object
     * @param string $action
     */
    protected function prettyPrint($object, $action)
    {
        $r = \Nette\Reflection\Method::from($object, $action);

        $this->printMethodSignatureAndDoc($r);

        $parameters = $r->getParameters();
        $this->out('Method Parameters:');
        $annotations = $r->hasAnnotation('param') ? $r->getAnnotations() : array();
        foreach ($parameters as $key => $parameter) {
            $doc = $this->getDocForParameter($parameter, $annotations, $key);
            $default = $this->getDefaultValueForParameter($parameter);
            $this->out('<light_blue>' . $doc . '</light_blue>' . $default);
        }
    }

    /**
     * @param \Nette\Reflection\Method $r
     */
    protected function printMethodSignatureAndDoc($r)
    {
        $this->lightGray()->out($r->getDescription());
        $this->out($r->__toString())->br();
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    protected function getDefaultValueForParameter($parameter)
    {
        $default = '';
        if ($parameter->isDefaultValueAvailable()) {
            $default = ' = ' . var_export($parameter->getDefaultValue(), true);
        }
        return $default;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param array $annotations
     * @param int $key
     * @return string
     */
    protected function getDocForParameter($parameter, $annotations, $key)
    {
        if (isset($annotations['param'][$key])) {
            return $annotations['param'][$key];
        }
        return $parameter->getName();
    }
} 