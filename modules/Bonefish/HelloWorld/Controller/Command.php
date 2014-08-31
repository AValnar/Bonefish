<?php
namespace Bonefish\HelloWorld\Controller;

class Command extends \Bonefish\Controller\Command
{

    /**
     * Print 'Hello World'
     */
    function helloCommand()
	{
        $this->out('Hello World!');
	}

}

