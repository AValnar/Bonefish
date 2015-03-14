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

        try {
            $dbConfig = $this->configurationManager->getConfiguration('Configuration.neon');
            $connection = new \Nette\Database\Connection(
                $dbConfig['database']['db_driver'] . ':host=' . $dbConfig['database']['db_host'] . ';dbname=' . $dbConfig['database']['db_name'],
                $dbConfig['database']['db_user'],
                $dbConfig['database']['db_pw'],
                array('lazy' => FALSE)
            );
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        $storage = $this->container->get('\Nette\Caching\Storages\FileStorage');
        $structure = new \Nette\Database\Structure($connection, $storage);
        $this->container->add('\Nette\Database\Structure', $structure);

        $convention = new \Nette\Database\Conventions\StaticConventions();
        $this->container->add('\Nette\Database\Conventions\StaticConventions', $convention);

        $context = new \Nette\Database\Context($connection, $structure, $convention, $storage);
        $this->container->add('\Nette\Database\Context', $context);

        $this->setModeStarted(self::MODE);
    }
} 