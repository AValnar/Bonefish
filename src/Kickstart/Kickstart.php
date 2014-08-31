<?php
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
 * @package Bonefish\Kickstart
 */

namespace Bonefish\Kickstart;

use \Nette\PhpGenerator as p;

class Kickstart
{

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $vendor;

    const TYPE_CONTROLLER = 'Controller';
    const TYPE_COMMAND = 'Command';


    /**
     * Kickstart a module
     *
     * @param string $name
     * @param string $vendor
     */
    public function module($name, $vendor)
    {
        $this->name = $name;
        $this->vendor = $vendor;

        // Create module directory
        $path = $this->environment->getFullModulePath() . '/' . $this->vendor . '/' . $this->name;
        $this->createDirectoryIfNotExist($path);

        $this->createController($path, self::TYPE_CONTROLLER);
        $this->createController($path, self::TYPE_COMMAND);
    }

    /**
     * @param string $path
     */
    protected function createDirectoryIfNotExist($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    /**
     * @param string $nameSpace
     * @return p\PhpFile
     */
    protected function createFileHeader($nameSpace = '')
    {
        $header = new p\PhpFile();
        $header->addNamespace($nameSpace);
        return $header;
    }

    /**
     * @return string
     */
    protected function createBootstrap()
    {
        $boostrap = new p\PhpFile();
        $content = 'return array(
    \'autoloader\' => array(\'' . $this->vendor . '\\' . $this->name . '\',\'' . $this->vendor . '/' . $this->name . '\')
);';
        return $boostrap->__toString() . PHP_EOL . $content;
    }

    /**
     * @param string $path
     * @param string $type
     */
    protected function createController($path, $type)
    {
        // Create Controller
        $controller = p\ClassType::from('\Bonefish\Kickstart\Templates\\' . $type);
        $controllerPath = $path . '/Controller';
        $controllerFilename = $controllerPath . '/' . $type . '.php';
        $header = $this->createFileHeader($this->vendor . '\\' . $this->name . '\Controller');
        $this->createDirectoryIfNotExist($controllerPath);
        $content = $header->__toString() . PHP_EOL . $controller->__toString() . PHP_EOL;
        file_put_contents($controllerFilename, $content);
        file_put_contents($path . '/bootstrap.php', $this->createBootstrap());
    }
} 