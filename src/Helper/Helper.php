<?php

/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-05-06
 * @package    Bonefish
 * @subpackage Helper
 */

namespace Bonefish\Helper;


class Helper
{

    /**
     * Evaluate value to bool
     *
     * @param mixed $var
     * @return bool
     */

    public function evalToBoolean($var)
    {
        switch ($var) {
            case $var === true:
            case $var == 1:
            case strtolower($var) == 'true':
            case strtolower($var) == 'on':
            case strtolower($var) == 'yes':
            case strtolower($var) == 'y':
                return true;
            default:
                return false;
        }
    }

    /**
     * Evaluate if var is "true" or "false" and convert to bool if so
     *
     * @param string $var
     * @return bool
     */

    public function evalTrueOrFalseStringToBoolean($var)
    {
        switch ($var) {
            case strtolower($var) == 'true':
                $var = true;
                break;
            case strtolower($var) == 'false':
                $var = false;
                break;
        }

        return $var;
    }

    /**
     * Check if value is a valid ID
     *
     * @param string|int $test
     * @return bool
     */

    public function isValidID($test)
    {
        return ((ctype_digit($test) || is_int($test)) && (int)$test > 0);
    }


    /**
     * Return s if value equals one
     *
     * @param int $amount
     * @return string
     */

    public function lazyS($amount)
    {
        return ((int)$amount == 1 ? '' : 's');
    }

    /**
     * Replace \n with <br />
     *
     * @param string $string
     * @return string
     */

    public function customNl2br($string)
    {
        return str_replace("\n", '<br />', $string);
    }

    /**
     * Convert boolean to integer
     *
     * @param bool $bool
     * @return int
     */

    public function convertBooleanToInt($bool)
    {
        return ($bool === true ? 1 : 0);
    }

    /**
     * Return stack trace as string
     *
     * @return string
     */

    public function stacktraceAsString()
    {
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_contents();
        ob_end_clean();

        return $trace;
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message
     * @param array $context
     * @return string
     */
    public function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            if (is_object($val)) {
                $r = new \ReflectionClass($val);
                if (!$r->hasMethod('__toString')) {
                    continue;
                }
            } else if (is_array($val)) {
                $val = print_r($val, true);
            }
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
} 