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
 */
class DrunkenBear extends CLIHelper implements ICLI
{
    const TYPE_EXPLAIN = 4;
    const TYPE_ALL = 1;
    const TYPE_VENDOR = 2;
    const TYPE_PACKAGE = 3;

    const TYPE_COMMAND = "Command";

    /**
     * Main handler
     */
    public function run()
    {
        $help = $this->needHelp();

        $this->filterInvalid($help);
        $this->filterHelp($help);

        $package = $this->environment->createPackage($this->vendor,$this->package);
        $this->filterExplain($help,$package);
        $this->execute($package,$this->action.self::TYPE_COMMAND,$this->buildParameterList());
    }

    /**
     * Filter out invalid commands
     *
     * @param int $help
     */
    private function filterInvalid($help)
    {
        if ($help == self::TYPE_VENDOR || $help == self::TYPE_PACKAGE) {
            $this->red()->out("Invalid Command!");
            exit(0);
        }
    }

    /**
     * Filter out help commands
     *
     * @param int $help
     */
    private function filterHelp($help)
    {
        if ($help == self::TYPE_ALL) {
            $packages = $this->environment->getAllPackages();
            $this->help($packages);
            exit(0);
        }
    }

    /**
     * Filter out explain commands
     *
     * @param int $help
     * @param \Bonefish\Core\Package $package
     */
    private function filterExplain($help,$package)
    {
        if ($help == self::TYPE_EXPLAIN) {
            $this->explain($package,$this->action.self::TYPE_COMMAND);
            exit(0);
        }
    }

    /**
     * Display all commands available in a package
     *
     * @param array <\Bonefish\Core\Package> $packages
     */
    public function help(array $packages)
    {
        foreach ($packages as $package) {
            $this->loadPackageAndDisplayActions($package);
        }
    }

    /**
     * Execute an action
     *
     * @param \Bonefish\Core\Package $package
     * @param string $action
     * @param array $parameters
     * @return mixed
     */
    public function execute($package, $action, $parameters = array())
    {
        $controller = $package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
        return call_user_func_array(array($controller, $action), $parameters);
    }

    /**
     * Explain an action
     *
     * @param \Bonefish\Core\Package $package
     * @param string $action
     */
    public function explain($package, $action)
    {
        $controller = $package->getController(\Bonefish\Core\Package::TYPE_COMMAND);
        $this->printer->prettyMethod($controller, $action);
    }

    /**
     * @return int
     */
    protected function needHelp()
    {
        for ($i = 1; $i < 5; ++$i) {
            if ($this->args[$i] == 'help') {
                return $i;
            }
        }
        return 0;
    }
} 