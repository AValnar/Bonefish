<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 09:12
 */

namespace Bonefish\Core\Mode;


class DatabaseMode extends NetteCacheMode
{
    const MODE = 'DatabaseMode';

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        if ($this->isModeStarted(self::MODE)) return;

        $connection = $this->initDatabaseConnection();

        $storage = $this->container->get('\Nette\Caching\Storages\FileStorage');
        $structure = $this->initStructure($connection, $storage);

        $convention = $this->initConventions();

        $this->initContext($connection, $structure, $convention, $storage);

        $this->setModeStarted(self::MODE);
    }

    /**
     * @return \Nette\Database\Connection
     */
    protected function initDatabaseConnection()
    {
        $dbConfig = $this->configurationManager->getConfiguration('Configuration.neon');
        return new \Nette\Database\Connection(
            $dbConfig['database']['db_driver'] . ':host=' . $dbConfig['database']['db_host'] . ';dbname=' . $dbConfig['database']['db_name'],
            $dbConfig['database']['db_user'],
            $dbConfig['database']['db_pw'],
            array('lazy' => FALSE)
        );
    }

    /**
     * @param $connection
     * @param $storage
     * @return \Nette\Database\Structure
     * @throws \Exception
     */
    protected function initStructure($connection, $storage)
    {
        $structure = new \Nette\Database\Structure($connection, $storage);
        $this->container->add('\Nette\Database\Structure', $structure);

        return $structure;
    }

    /**
     * @return \Nette\Database\Conventions\StaticConventions
     * @throws \Exception
     */
    protected function initConventions()
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
     */
    protected function initContext($connection, $structure, $convention, $storage)
    {
        $context = new \Nette\Database\Context($connection, $structure, $convention, $storage);
        $this->container->add('\Nette\Database\Context', $context);
    }
} 