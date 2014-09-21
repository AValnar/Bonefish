<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 21.09.14
 * Time: 10:10
 */

namespace Bonefish\CLI;


class Printer extends \JoeTannenbaum\CLImate\CLImate
{
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