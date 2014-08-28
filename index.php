<?php

/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-08-27
 * @package    Bonefish
 */

$baseDir = dirname(__FILE__);
$composerAutoload = $baseDir . '/vendor/autoload.php';

if (!file_exists($composerAutoload))
    die('Please run Composer install first!');

require $baseDir . '/vendor/autoload.php';


