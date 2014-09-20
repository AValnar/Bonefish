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
     * @param bool $path
     * @return \Respect\Config\Container
     * @throws \InvalidArgumentException
     */
    public function getConfiguration($name,$path = FALSE)
    {
        $path = $this->getPath($name,$path);

        if (!isset($this->configurations[$name])) {
            if (!file_exists($path)) {
                throw new \InvalidArgumentException('Configuration does not exist!');
            }
            $this->configurations[$name] = new \Respect\Config\Container($path);
        }
        return $this->configurations[$name];
    }

    protected function getPath($name,$path)
    {
        if (!$path) {
            return $this->environment->getFullConfigurationPath() . '/' . $name;
        }
        return $name;
    }
} 