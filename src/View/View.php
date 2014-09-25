<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 04.09.14
 * Time: 19:50
 */

namespace Bonefish\View;


use Bonefish\Viewhelper\AbstractViewhelper;

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

    /**
     * @var array
     */
    protected $macros = array();

    public function __init()
    {
        $this->setLayout('Default.latte');
    }

    public function assign($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function render()
    {
        $this->loadDefaultMacros();
        $this->loadMacros();
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

    /**
     * @param AbstractViewhelper $helper
     */
    public function addMacro(AbstractViewhelper $helper)
    {
        $this->macros[] = $helper;
    }

    protected function loadMacros()
    {
        $macroSet = new \Latte\Macros\MacroSet($this->latte->getCompiler());
        /** @var AbstractViewhelper $macro */
        foreach ($this->macros as $macro) {
            if ($macro->getHasEnd()) {
                $macroSet->addMacro(
                    $macro->getName(),
                    array($macro, 'getStart'),
                    array($macro, 'getEnd')
                );
            } else {
                $macroSet->addMacro(
                    $macro->getName(),
                    array($macro, 'getStart')
                );
            }
        }

    }

    protected function loadDefaultMacros()
    {
        $defaults = require $this->environment->getFullConfigurationPath() . '/Viewhelper.default.php';
        foreach ($defaults as $macro) {
            $this->addMacro($this->container->get($macro));
        }
    }
} 