<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 31.08.14
 * Time: 13:59
 */

namespace Bonefish\Router;


class Router
{

    /**
     * @var \League\Url\AbstractUrl
     */
    protected $url;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var \Bonefish\Autoloader\Autoloader
     */
    protected $autoloader;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var bool|string
     */
    protected $vendor = FALSE;

    /**
     * @var bool|string
     */
    protected $package = FALSE;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $parameter = array();

    /**
     * @var \Respect\Config\Container
     */
    protected $config;

    /**
     * @param \League\Url\UrlImmutable $url
     * @param string $baseDir
     * @param \Bonefish\Autoloader\Autoloader $autoloader
     * @param \Respect\Config\Container $config
     */
    public function __construct($url,$baseDir,$autoloader,$config)
    {
        $this->url = $url;
        $this->baseDir = $baseDir;
        $this->autoloader = $autoloader;
        $this->config = $config;
    }

    public function route()
    {
        $path = $this->resolveRoute();

        if (file_exists($path)) {
            $bootstrap = require $path;
        } else {
            // Show 404
            die('No Route found.');
        }

        if (isset($bootstrap['autoloader'])) {
            $this->autoloader->addNamespace($bootstrap['autoloader'][0],$this->baseDir.$bootstrap['autoloader'][1]);
        }

        $controllerClass =  '\\'.$this->vendor.'\\'.$this->package.'\Controller\Controller';

        $controller = $this->container->create($controllerClass);
        $action = $this->action.'Action';
        if (is_callable(array($controller,$action))) {
            $this->sortParameters($controller,$action);
            call_user_func_array(array($controller,$action),$this->parameter);
        } else {
            $controller->indexAction();
        }
    }

    /**
     * @return string
     */
    protected function resolveRoute()
    {
        $path = urldecode($this->url->getPath());
        $parts = explode('/',$path,2);

        foreach($parts as $part) {
            $ex = explode(':',$part);
            if (isset($ex[0]) && isset($ex[1])) {
                switch($ex[0]) {
                    case 'v':
                        $this->vendor = $ex[1];
                        break;
                    case 'p':
                        $this->package = $ex[1];
                        break;
                    case 'a':
                        $this->action = $ex[1];
                        break;
                    default:
                        $this->parameter[$ex[0]] = $ex[1];
                        break;
                }
            }
        }

        if (!$this->vendor) {
            $this->vendor = $this->config->vendor;
        }

        if (!$this->package) {
            $this->package = $this->config->package;
        }

        return $this->baseDir.'/modules/'.$this->vendor.'/'.$this->package.'/bootstrap.php';
    }

    /**
     * @param mixed $controller
     * @param string $action
     */
    protected function sortParameters($controller,$action)
    {
        $r = \Nette\Reflection\Method::from($controller,$action);
        $userParams = array();
        $methodParams = $r->getParameters();
        foreach($methodParams as $key => $parameter) {
            if (isset($this->parameter[$parameter->getName()])) {
                $userParams[$key] = $this->parameter[$parameter->getName()];
            }
        }
        $this->parameter = $userParams;
    }
} 