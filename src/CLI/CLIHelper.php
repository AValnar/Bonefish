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
 * @date       2014-10-02
 * @package Bonefish\CLI
 * @method mixed border(string $char = '', integer $length = '')
 * @method mixed out(string $text)
 * @method mixed br
 * @method mixed red
 */
class CLIHelper extends \League\CLImate\CLImate
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
     * @var \Bonefish\CLI\Printer
     * @inject
     */
    public $printer;

    /**
     * @var \Bonefish\Reflection\Helper
     * @inject
     */
    public $parser;

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
     * Set an argument if it is set otherwise set empty
     *
     * @param string $key
     * @return string
     */
    protected function setArgIfIsset($key)
    {
        return isset($this->args[$key]) ? $this->args[$key] : '';
    }

    /**
     * Take all arguments and push them into an array.
     *
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
     * Print vendor and name of supplied package and list all actions
     *
     * @param \Bonefish\Core\Package $package
     */
    protected function loadPackageAndDisplayActions($package)
    {
        $this->out('<light_red>Vendor</light_red>: ' . $package->getVendor() . ' <light_red>Package</light_red>: ' . $package->getName());
        $this->border();
        $this->displayActionsFromPackage($package);
        $this->br();
    }

    /**
     * Get all controller actions and display how they should be called without parameters.
     *
     * @param \Bonefish\Core\Package $package
     */
    protected function displayActionsFromPackage($package)
    {
        $controller = $package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
        $reflection = new \ReflectionClass($controller);

        $actions = $this->parser->getSuffixMethods('Command',$reflection);

        foreach ($actions as $action) {
            $this->out($package->getVendor() . ' ' . $package->getName() . ' ' . $action);
        }
    }
} 