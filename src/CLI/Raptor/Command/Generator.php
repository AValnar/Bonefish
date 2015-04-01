<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 16.03.2015
 * Time: 20:44
 */

namespace Bonefish\CLI\Raptor\Command;


use Bonefish\AbstractTraits\Parameters;
use Bonefish\DI\IContainer;

class Generator
{
    use Parameters;

    /**
     * @var IContainer
     * @Bonefish\Inject
     */
    public $container;

    protected $commandTypes = array(
        '\Bonefish\CLI\Raptor\Command\HelpCommand',
        '\Bonefish\CLI\Raptor\Command\ListCommand',
        '\Bonefish\CLI\Raptor\Command\ExplainCommand',
        '\Bonefish\CLI\Raptor\Command\ExecuteCommand'
    );

    public function getCommand()
    {
        $args = $this->getParameters();

        foreach($this->commandTypes as $type) {
            /** @var ICommand $command */
            $command = $this->container->create($type);
            $command->setParameters($args);
            if ($command->isValid()) {
                return $command;
            }
        }

        throw new \InvalidArgumentException('Could not create a valid command!');
    }

} 