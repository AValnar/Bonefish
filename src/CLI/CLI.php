<?php

namespace Bonefish\CLI;

/**
 * Class CLI
 * @package Bonefish\CLI
 * @method mixed border(string $char = '', integer $length = '')
 */
class CLI extends \JoeTannenbaum\CLImate\CLImate
{

    /**
     * @var array
     */
    protected $args;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string|bool
     */
    protected $package;

    /**
     * @var string|bool
     */
    protected $action;

    const MODULE_PATH = '/modules';

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @param array $args
     * @param string $baseDir
     */
    public function __construct(array $args, $baseDir)
    {
        parent::__construct();
        $this->args = $args;
        $this->vendor = $args[1];
        $this->package = isset($args[2]) ? $args[2] : FALSE;
        $this->action = isset($args[3]) ? $args[3] : FALSE;
        $this->baseDir = $baseDir;
    }

    /**
     * CLI handler
     */
    public function execute()
    {
        $this->lightGreen()->out('Welcome to Bonefish!')->br();

        if (strtolower($this->vendor) == 'help') {
            $this->listCommands();
        } else {
            if ($this->validateVendorPackageArgs()) {
                $this->executeCommand($this->vendor);
            }
        }
    }

    /**
     * @param string $package
     */
    protected function listCommands($package = '')
    {
        $path = $this->baseDir . self::MODULE_PATH;

        if ($package != '') {
            $path .= $package;
        }

        $this->out('The following commands are present in your system:');

        $vendorIterator = new \DirectoryIterator($path);
        foreach ($vendorIterator as $vendor) {
            if (!$vendor->isDir() || $vendor->isDot()) continue;
            $packageIterator = new \DirectoryIterator($this->baseDir . self::MODULE_PATH . '/' . $vendor);
            foreach ($packageIterator as $package) {
                if (!$package->isDir() || $package->isDot()) continue;
                $path = $this->getCommandControllerPath($vendor->__toString(), $package->__toString());
                if (file_exists($path)) {
                    $this->parseCommandsFromFile($path);
                }
            }
        }

    }

    protected function executeCommand()
    {
        if ($this->action == 'help') {
            $this->listCommands('/' . $this->vendor . '/' . $this->package);
            return;
        }

        // check if action exists
        require_once $this->getCommandControllerPath();
        $name = $this->getCommandClassForVendorPackage($this->args[1], $this->args[2]);
        $obj = $this->container->create($name);
        $action = $this->args[3] . 'Command';
        if (!is_callable(array($obj, $action))) {
            $this->out('Invalid action!');
            return;
        }
        if (isset($this->args[4]) && $this->args[4] == 'help') {
            $this->prettyPrint($obj, $action);
        } else {
            $parameters = count($this->args) - 4;
            switch ($parameters) {
                case 0:
                    $obj->{$action}();
                    break;
                case 1:
                    $obj->{$action}($this->args[4]);
                    break;
                case 2:
                    $obj->{$action}($this->args[4], $this->args[5]);
                    break;
                case 3:
                    $obj->{$action}($this->args[4], $this->args[5], $this->args[6]);
                    break;
                case 4:
                    $obj->{$action}($this->args[4], $this->args[5], $this->args[6], $this->args[7]);
                    break;
                case 5:
                    $obj->{$action}($this->args[4], $this->args[5], $this->args[6], $this->args[7], $this->args[8]);
                    break;
            }
        }
    }

    /**
     * @return bool
     */
    protected function validateVendorPackageArgs()
    {
        if (!isset($this->args[2]) || !isset($this->args[3])) {
            $this->out('Incomplete command!');
            return false;
        }

        // check if controller exists
        if (!file_exists($this->getCommandControllerPath())) {
            $this->out('Invalid command!');
            return false;
        }

        return true;
    }

    /**
     * @param string $path
     */
    protected function parseCommandsFromFile($path)
    {
        require_once $path;
        $vendor = $this->getVendorFromControllerFromPath($path);
        $package = $this->getPackageFromControllerFromPath($path);
        $r = new \ReflectionClass($this->getCommandClassForVendorPackage($vendor, $package));
        $this->out('<light_red>Vendor</light_red>: ' . $vendor . ' <light_red>Module</light_red>: ' . $package);
        $this->border();
        $this->getActionsFromController($r, $vendor, $package);
        $this->br();
    }

    /**
     * @param bool|string $vendor
     * @param bool|string $package
     * @return string
     */
    protected function getCommandControllerPath($vendor = FALSE, $package = FALSE)
    {
        if (!$vendor && !$package) {
            $vendor = $this->vendor;
            $package = $this->package;
        }

        return $this->baseDir . self::MODULE_PATH . '/' . $vendor . '/' . $package . '/Controller/Command.php';
    }

    /**
     * @param \ReflectionClass $reflection
     * @param string $vendor
     * @param string $package
     */
    protected function getActionsFromController(\ReflectionClass $reflection, $vendor, $package)
    {
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            preg_match('/([a-zA-Z]*)Command/', $method->getName(), $match);
            if (isset($match[1])) {
                $this->out($vendor . ' ' . $package . ' ' . $match[1]);
            }
        }
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getVendorFromControllerFromPath($path)
    {
        return $this->getParts($path)[1];
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getPackageFromControllerFromPath($path)
    {
        return $this->getParts($path)[2];
    }

    /**
     * @param string $path
     * @return array
     */
    private function getParts($path)
    {
        return explode('/', str_replace($this->baseDir . self::MODULE_PATH, '', $path));
    }

    /**
     * @param string $vendor
     * @param string $package
     * @return string
     */
    protected function getCommandClassForVendorPackage($vendor, $package)
    {
        return '\\' . $vendor . '\\' . $package . '\Controller\Command';
    }

    protected function prettyPrint($oject, $action)
    {
        $r = \Nette\Reflection\Method::from($oject, $action);

        $this->lightGray($r->getDescription());
        $this->out($r->__toString())->br();

        $parameters = $r->getParameters();
        $this->out('Method Parameters:');
        $annotations = $r->hasAnnotation('param') ? $r->getAnnotations() : FALSE;
        foreach ($parameters as $key => $parameter) {
            $text = isset($annotations['param'][$key]) ? $annotations['param'][$key] : $parameter->getName();
            unset($default);
            if ($parameter->isDefaultValueAvailable()) {
                $default = ' = ' . var_export($parameter->getDefaultValue(), true);
            }
            $this->out('<light_blue>' . $text . '</light_blue>' . (isset($default) ? $default : ''));
        }
    }
} 