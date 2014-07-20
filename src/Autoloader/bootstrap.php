<?php

require 'Autoloader.php';

$autoloader = new Bonefish\Autoloader\Autoloader();
$autoloader->register();

$autoloader->addNamespace('Bonefish','src');
$autoloader->addNamespace('Bonefish\Tests','tests');