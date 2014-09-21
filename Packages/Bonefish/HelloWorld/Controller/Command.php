<?php
namespace Bonefish\HelloWorld\Controller;

class Command extends \Bonefish\Controller\Command
{

    /**
     * TODO: implement mainCommand
     */
    public function mainCommand()
    {
        $this->out('Hello World');
    }

    /**
     * Greet someone
     *
     * @param string $name
     */
    public function greetCommand($name = 'Joe')
    {
        $this->out('Hello ' . $name);
    }

}

