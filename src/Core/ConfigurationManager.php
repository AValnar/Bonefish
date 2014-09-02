<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 01.09.14
 * Time: 18:45
 */

namespace Bonefish\Core;


class ConfigurationManager
{

    protected $configurations = array();

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @param string $name
     * @return \Respect\Config\Container
     * @throws \InvalidArgumentException
     */
    public function getConfiguration($name)
    {
        if (!isset($this->configurations[$name])) {
            $path = $this->environment->getFullConfigurationPath() . '/' . $name;
            if (!file_exists($path)) {
                throw new \InvalidArgumentException('Configuration does not exist!');
            }
            $this->configurations[$name] = new \Respect\Config\Container($path);
        }
        return $this->configurations[$name];
    }
} 