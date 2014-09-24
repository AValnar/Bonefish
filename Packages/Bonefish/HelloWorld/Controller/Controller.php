<?php
namespace Bonefish\HelloWorld\Controller;

class Controller extends \Bonefish\Controller\Base
{
    public function indexAction()
    {
        $this->view->render();
    }

    /**
     * @param $name
     */
    public function helloAction($name)
    {
        echo 'Hello ' . $name . '!';
    }

}

