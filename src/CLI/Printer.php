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
class Printer extends \League\CLImate\CLImate
{
    /**
     * @param mixed $object
     * @param string $method
     * @param bool $plain don't like colors ?
     * @param bool $return
     * @param bool $supressOutput
     * @return string
     */
    public function prettyMethod($object, $method, $plain = FALSE, $supressOutput = FALSE)
    {
        $r = \Nette\Reflection\Method::from($object, $method);
        $parameters = $r->getParameters();
        $annotations = $r->hasAnnotation('param') ? $r->getAnnotations() : array();

        $output = '';

        if ($r->getDescription() != '') {
            $output .= '<light_green>' . $r->getDescription() . '</light_green>' . PHP_EOL;
        }

        $output .= $r->getName() . PHP_EOL;

        if (!empty($parameters)) {
            $output .= PHP_EOL;
            $output .= 'Method Parameters:' . PHP_EOL;

            foreach ($parameters as $key => $parameter) {
                $output .= $this->output($parameter, $key, $annotations);
            }
        }

        if (!$supressOutput) {
            $this->output($output,$plain);
        }

        return $output;
    }

    protected function printParameter($parameter, $key, $annotations)
    {
        $doc = $this->getDocForParameter($parameter, $annotations, $key);
        $default = $this->getDefaultValueForParameter($parameter);

        return '<light_blue>' . $doc . '</light_blue>' . $default . PHP_EOL;
    }

    /**
     * @param $output
     * @param $plain
     */
    protected  function output($output, $plain)
    {
        if ($plain) {
            echo $output;
        } else {
            $this->out($output);
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