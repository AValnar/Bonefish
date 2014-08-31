<?php
namespace Bonefish\HelloWorld\Controller;

class Controller extends \Bonefish\Controller\Base
{

	/**
	 * TODO: implement indexAction
	 */
	public function indexAction()
	{
        echo 'Hello World';
	}

    /**
     * @param $name
     */
    public function helloAction($name)
    {
        echo 'Hello '.$name.'!';
    }

}

