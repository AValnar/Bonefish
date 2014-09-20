<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 07.09.14
 * Time: 12:21
 */

namespace Bonefish\ORM;


abstract class Repository extends \YetORM\Repository
{

    /**
     * @var \Nette\Database\Context
     * @inject eagerly
     */
    public $context;

    public function __construct()
    {

    }

    public function __init()
    {
        parent::__construct($this->context);
    }
} 