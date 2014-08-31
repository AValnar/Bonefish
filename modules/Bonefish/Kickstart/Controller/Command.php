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
	 * TODO: implement mainCommand
	 */
	function moduleCommand($name,$vendor)
	{
        $kickstarter = $this->container->get('\Bonefish\Kickstart\Kickstart');
        $kickstarter->module($name,$vendor);
	}

}

