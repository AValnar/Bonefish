<?php

namespace Bonefish\Core;

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
 * @date       2014-09-01
 * @package Bonefish\Core
 */
class ConfigurationManager
{

    /**
     * @var array
     */
    protected $configurations = array();

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var \Nette\Neon\Neon
     * @inject
     */
    public $neon;

    /**
     * @param string $name
     * @param bool $path
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getConfiguration($name, $path = FALSE)
    {
        if (!isset($this->configurations[$name])) {
            $path = $this->getPath($name, $path);
            if (!file_exists($path)) {
                throw new \InvalidArgumentException('Configuration does not exist!');
            }
            $config = file_get_contents($path);
            $this->configurations[$name] = $this->neon->decode($config);
        }
        return $this->configurations[$name];
    }

    /**
     * @param $name
     * @param $data
     * @param bool $path
     * @throws \InvalidArgumentException
     */
    public function writeConfiguration($name, $data, $path = FALSE)
    {
        $path = $this->getPath($name, $path);
        $file = $this->neon->encode($data);

        file_put_contents($path, $file);

        if (isset($this->configurations[$name])) {
            $this->configurations[$name] = $data;
        }
    }

    /**
     * @param string $name
     * @param bool $path
     * @return string
     */
    protected function getPath($name, $path)
    {
        if (!$path) {
            return $this->environment->getFullConfigurationPath() . '/' . $name;
        }
        return $name;
    }
} 