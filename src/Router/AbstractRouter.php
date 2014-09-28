<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 29.09.2014
 * Time: 20:01
 */

namespace Bonefish\Router;


abstract class AbstractRouter
{
    /**
     * @var array
     */
    protected $parameter = array();

    /**
     * @var \League\Url\AbstractUrl
     */
    protected $url;

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @param \League\Url\UrlImmutable $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $action
     * @param mixed $controller
     */
    protected function callControllerAction($action, $controller)
    {
        if (is_callable(array($controller, $action))) {
            $this->sortParameters($controller, $action);
            call_user_func_array(array($controller, $action), $this->parameter);
        } else {
            $controller->indexAction();
        }
    }

    /**
     * @param mixed $controller
     * @param string $action
     */
    protected function sortParameters($controller, $action)
    {
        $r = \Nette\Reflection\Method::from($controller, $action);
        $userParams = array();
        $methodParams = $r->getParameters();
        foreach ($methodParams as $key => $parameter) {
            if (isset($this->parameter[$parameter->getName()])) {
                $userParams[$key] = $this->parameter[$parameter->getName()];
            }
        }
        $this->parameter = $userParams;
    }
} 