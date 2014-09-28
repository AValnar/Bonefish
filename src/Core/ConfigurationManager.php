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
    public function getConfiguration($name,$path = FALSE)
    {
        if (!isset($this->configurations[$name])) {
            $path = $this->getPath($name,$path);
            if (!file_exists($path)) {
                throw new \InvalidArgumentException('Configuration does not exist!');
            }
            $config = file_get_contents($path);
            $this->configurations[$name] = $this->neon->decode($config);
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