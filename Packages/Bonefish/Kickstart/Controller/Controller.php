<?php
namespace Bonefish\Kickstart\Controller;

class Controller extends \Bonefish\Controller\Base
{

    /**
     * TODO: implement indexAction
     */
    public function indexAction()
    {
        echo 'Please use the command line tool to use Bonefish/Kickstart';
    }

    /**
     * @param string $foo
     * @param string $bar
     */
    public function unitAction($foo, $bar)
    {
        return $foo . $bar;
    }

}

