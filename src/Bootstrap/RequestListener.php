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
use Bonefish\Router\Request\Request;

final class RequestListener extends AbstractEventStrapListener
{


    /**
     * @param array $options
     */
    public function __construct($options)
    {

    }

    /**
     * @param Event[] $events
     */
    public function onEventFired(array $events = [])
    {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            $request = new Request('GET', '/');
        } else {
            $request = Request::fromServer();
        }

        $this->emit($request);
    }
}