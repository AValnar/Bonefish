<?php


namespace Bonefish\Core\Mode;


use Bonefish\AbstractTraits\Parameters;
use Bonefish\DependencyInjection\Container;

abstract class AbstractMode
{
    use Parameters;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     * @return self
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Init needed framework stack
     */
    abstract public function init();

    /**
     * @param string $mode
     * @return bool
     */
    protected function isModeStarted($mode)
    {
        $parameters = $this->getParameters();

        return isset($parameters[$mode]);
    }

    /**
     * @param string $mode
     */
    protected function setModeStarted($mode)
    {
        $parameters = $this->getParameters();
        $parameters[$mode] = true;
        $this->setParameters($parameters);
    }

    /**
     * @param string $path
     */
    protected function createDir($path)
    {
        if (!file_exists($path)) {
            mkdir($path);
        }
    }
} 