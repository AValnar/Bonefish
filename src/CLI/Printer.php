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
 * @date       2014-09-21
 * @package Bonefish\CLI
 */
class Printer extends \JoeTannenbaum\CLImate\CLImate
{
    /**
     * @param mixed $object
     * @param string $method
     */
    public function prettyMethod($object, $method)
    {
        $r = \Nette\Reflection\Method::from($object, $method);
        $this->lightGreen()->out($r->getDescription());
        $this->out($r->getName());
        $parameters = $r->getParameters();
        $this->br();
        $this->out('Method Parameters:');
        $annotations = $r->hasAnnotation('param') ? $r->getAnnotations() : array();
        foreach ($parameters as $key => $parameter) {
            $doc = $this->getDocForParameter($parameter, $annotations, $key);
            $default = $this->getDefaultValueForParameter($parameter);
            $this->out('<light_blue>' . $doc . '</light_blue>' . $default);
        }
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