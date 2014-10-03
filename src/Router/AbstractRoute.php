<?php

namespace Bonefish\Router;

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
 * @date       2014-10-03
 * @package Bonefish\Router
 */
abstract class AbstractRoute
{
    const TYPE_404 = 'notfound';
    const TYPE_403 = 'notallowed';
    const TYPE_DEFAULT = 'default';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Bonefish\Core\ConfigurationManager
     * @inject
     */
    public $configurationManager;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getDTO()
    {
        $config = $this->getConfiguration();
        return new DTO(
            $config['vendor'],
            $config['package'],
            $config['controller'],
            $config['action']
        );
    }

    protected function getConfiguration()
    {
        $config = $this->configurationManager->getConfiguration('Configuration.neon');
        return $config['route'][$this->type];
    }

} 