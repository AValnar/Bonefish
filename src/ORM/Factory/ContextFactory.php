<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 02.04.2015
 * Time: 18:16
 */

namespace Bonefish\ORM\Factory;


use Bonefish\Core\ConfigurationManager;
use Bonefish\DI\IContainer;
use Bonefish\Factory\IFactory;
use Bonefish\ORM\Context;

class ContextFactory implements IFactory
{
    /**
     * @var ConfigurationManager
     * @Bonefish\Inject
     */
    public $configurationManager;

    /**
     * @var IContainer
     * @Bonefish\Inject
     */
    public $container;

    /**
     * @var array
     */
    protected $basicConfiguration;

    /**
     * Return an object with fully injected dependencies
     *
     * @param array $parameters
     * @return mixed
     */
    public function create(array $parameters = array())
    {
        $this->basicConfiguration = $this->configurationManager->getConfiguration('Configuration.neon');

        $connection = $this->getDatabaseConnection();

        $storage = $this->container->get('\Nette\Caching\Storages\FileStorage');
        $structure = $this->getStructure($connection, $storage);

        $convention = $this->getConventions();

        return $this->getContext($connection, $structure, $convention, $storage);
    }

    /**
     * @return \Nette\Database\Connection
     */
    protected function getDatabaseConnection()
    {
        $connection = new \Nette\Database\Connection(
            $this->basicConfiguration['database']['db_driver'] . ':host=' . $this->basicConfiguration['database']['db_host'] . ';dbname=' . $this->basicConfiguration['database']['db_name'],
            $this->basicConfiguration['database']['db_user'],
            $this->basicConfiguration['database']['db_pw'],
            array('lazy' => TRUE)
        );
        $this->container->add('\Nette\Database\Connection', $connection);
        return $connection;
    }

    /**
     * @param $connection
     * @param $storage
     * @return \Nette\Database\Structure
     * @throws \Exception
     */
    protected function getStructure($connection, $storage)
    {
        $structure = new \Nette\Database\Structure($connection, $storage);
        $this->container->add('\Nette\Database\Structure', $structure);

        return $structure;
    }

    /**
     * @return \Nette\Database\Conventions\StaticConventions
     * @throws \Exception
     */
    protected function getConventions()
    {
        $convention = new \Nette\Database\Conventions\StaticConventions();
        $this->container->add('\Nette\Database\Conventions\StaticConventions', $convention);

        return $convention;
    }

    /**
     * @param $connection
     * @param $structure
     * @param $convention
     * @param $storage
     * @throws \Exception
     * @return Context
     */
    protected function getContext($connection, $structure, $convention, $storage)
    {
        $context = new Context($connection, $structure, $convention, $storage);
        $this->container->add('\Bonefish\ORM\Context', $context);
        return $context;
    }
}