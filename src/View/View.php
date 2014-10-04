<?php

namespace Bonefish\View;

use Bonefish\Viewhelper\AbstractViewhelper;

/**
 * Copyright (C) 2014  Alexander Schmidt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-09-04
 * @package Bonefish\View
 */
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

    /**
     * @param string $name
     * @param mixed $value
     */
    public function assign($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function render()
    {
        $this->loadDefaultMacros();
        $this->loadPackageMacros();
        $this->loadMacros();
        $this->latte->render(
            $this->environment->getPackage()->getPackagePath() . '/Layouts/' . $this->layout,
            $this->parameters
        );
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

    /**
     * @param array $config
     */
    public function loadMacrosFromConfiguration(array $config)
    {
        foreach ($config as $macro) {
            $this->addMacro($this->container->get($macro));
        }
    }

    protected function loadDefaultMacros()
    {
        $defaults = $this->configurationManager->getConfiguration('Viewhelper.neon');
        $this->loadMacrosFromConfiguration($defaults['global']);
    }

    protected function loadPackageMacros()
    {
        try {
            $path = $this->environment->getPackage()->getPackagePath() . '/Configuration/Viewhelper.neon';
            $config = $this->configurationManager->getConfiguration($path, true);
            $this->loadMacrosFromConfiguration($config);
        } catch (\Exception $e) {
            // Package has no custom viewhelpers to load by default
        }
    }
} 