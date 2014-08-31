<?php
namespace Bonefish\Kickstart\Controller;

class Command extends \Bonefish\Controller\Command
{

	/**
	 * TODO: implement mainCommand
	 */
	function moduleCommand($name,$vendor)
	{
        $kickstarter = new \Bonefish\Kickstart\Kickstart($this->baseDir);
        $kickstarter->module($name,$vendor);
	}

}

