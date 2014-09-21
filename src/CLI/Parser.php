<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 21.09.14
 * Time: 10:04
 */

namespace Bonefish\CLI;


class Parser
{

    public function getSuffixMethods($suffix, \ReflectionClass $reflection)
    {
        return $this->getRegExMethods('/([a-zA-Z]*)' . $suffix . '/', $reflection);
    }

    public function getPrefixMethods($prefix, \ReflectionClass $reflection)
    {
        return $this->getRegExMethods('/' . $prefix . '([a-zA-Z]*)/', $reflection);
    }

    public function getRegExMethods($regEx, \ReflectionClass $reflection)
    {
        $return = array();

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            preg_match($regEx, $method->getName(), $match);
            if (isset($match[1])) {
                $return[] = $match[1];
            }
        }

        return $return;
    }
} 