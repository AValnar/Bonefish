<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 28.08.14
 * Time: 20:54
 */

namespace Bonefish\Controller;


class Command extends \JoeTannenbaum\CLImate\CLImate
{
    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @param string $baseDir
     */
    public function __construct($baseDir)
    {
        parent::__construct();
        $this->baseDir = $baseDir;
    }

    /**
     * @return mixed
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

} 