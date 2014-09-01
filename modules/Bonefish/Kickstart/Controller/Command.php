<?php
namespace Bonefish\Kickstart\Controller;

class Command extends \Bonefish\Controller\Command
{

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @param string $name
     * @param string $vendor
     */
    function moduleCommand($name,$vendor)
	{
        $kickstarter = $this->container->get('\Bonefish\Kickstart\Kickstart');
        $kickstarter->module($name,$vendor);
	}

    /**
     * @param string $test
     */
    public function unitCommand($test = '',$var)
    {

    }

}

