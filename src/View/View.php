<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 04.09.14
 * Time: 19:50
 */

namespace Bonefish\View;


class View
{

    /**
     * @var \Latte\Engine
     * @inject
     */
    public $latte;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $layout;

    /**
     * @var \Bonefish\Core\Environment
     * @inject
     */
    public $environment;

    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @var \Bonefish\DependencyInjection\Container
     * @inject
     */
    public $container;

    public function __init()
    {
        $config = $this->configurationManager->getConfiguration('Basic.ini');
        $this->setLayout('Default.latte');
        $this->assign('config', $config);
        $this->assign('env', $this->environment);
    }

    public function assign($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function render()
    {
        $this->parameters['view'] = $this;
        $this->latte->render($this->environment->getPackage()->getPackagePath() . '/Layouts/' . $this->layout, $this->parameters);
    }

    /**
     * @param string $layout
     * @return self
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    public function base()
    {
        return '<base href="' . $this->parameters['config']->baseUrl . $this->environment->getPackage()->getPackageUrlPath() . '/Layouts/" />';
    }
} 