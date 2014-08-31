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
    protected $baseDir;

    public function __construct($baseDir)
    {
        parent::__construct();
        $this->baseDir = $baseDir;
    }
} 