<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 01.04.2015
 * Time: 18:33
 */

namespace Bonefish\Core\Factory;


use Bonefish\Core\Environment;
use Bonefish\Core\Kernel;
use Bonefish\Factory\IFactory;

class EnvironmentFactory implements IFactory
{
    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @Bonefish\Inject
     */
    public $configurationManager;

    /**
     * Return an object with fully injected dependencies
     *
     * @param array $parameters
     * @return mixed
     */
    public function create(array $parameters = array())
    {
        $environment = new Environment();
        $environment->setBasePath(Kernel::getBaseDir());
        return $environment;
    }
}