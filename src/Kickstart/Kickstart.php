<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 28.08.14
 * Time: 19:40
 */

namespace Bonefish\Kickstart;

use \Nette\PhpGenerator as p;

class Kickstart {

    protected $baseDir;
    protected $name;
    protected $vendor;

    const MODULE_DIR = '/modules';
    const TYPE_CONTROLLER = 'Controller';
    const TYPE_COMMAND = 'Command';


    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * Kickstart a module
     *
     * @param string $name
     * @param string $vendor
     */
    public function module($name,$vendor)
    {
        $this->name = $name;
        $this->vendor = $vendor;

        // Create module directory
        $path = $this->baseDir.self::MODULE_DIR.'/'.$this->vendor.'/'.$this->name;
        $this->createDirectoryIfNotExist($path);

        $this->createController($path,self::TYPE_CONTROLLER);
        $this->createController($path,self::TYPE_COMMAND);
    }

    protected function createDirectoryIfNotExist($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    protected function createFileHeader($nameSpace = '')
    {
        $header = new p\PhpFile();
        $header->addNamespace($nameSpace);
        return $header;
    }

    protected function createController($path,$type)
    {
        // Create Controller
        $controller = p\ClassType::from('\Bonefish\Kickstart\Templates\\'.$type);
        $controllerPath = $path.'/Controller';
        $controllerFilename = $controllerPath.'/'.$type.'.php';
        $header = $this->createFileHeader($this->vendor.'\\'.$this->name.'\Controller');
        $this->createDirectoryIfNotExist($controllerPath);
        $content = $header->__toString().PHP_EOL.$controller->__toString().PHP_EOL;
        file_put_contents($controllerFilename,$content);
    }
} 