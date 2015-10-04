<?php
/**
 * Copyright (C) 2015  Alexander Schmidt
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
 * @copyright  Copyright (c) 2015, Alexander Schmidt
 * @date       04.10.2015
 */

namespace Bonefish\Bootstrap;


use AValnar\EventDispatcher\Event;
use AValnar\EventStrap\Listener\AbstractEventStrapListener;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tracy\Debugger;

class TracyListener extends AbstractEventStrapListener
{
    /**
     * @var string
     */
    private $mode;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $email;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults([
            'mode' => null,
            'directory' => null,
            'email' => null
        ]);

        $options = $optionsResolver->resolve($options);

        $this->mode = $options['mode'];
        $this->directory = $options['directory'];
        $this->email = $options['email'];
    }

    /**
     * @param Event[] $events
     */
    public function onEventFired(array $events = [])
    {
        Debugger::enable($this->mode, $this->directory, $this->email);

        $this->emit(null);
    }
}