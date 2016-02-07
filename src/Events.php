<?php
declare(strict_types = 1);
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
 * @copyright  Copyright (c) 2016, Alexander Schmidt
 * @date       07.02.16
 */

namespace Bonefish;


final class Events
{
    const BOOT_INIT = 'bonefish.boot';
    const CACHE_INIT = 'bonefish.cache.init';
    const ANNOTATION_READER_INIT = 'bonefish.annotations.init';
    const REFLECTION_SERVICE_INIT = 'bonefish.reflection.init';
    const CONTAINER_INIT = 'bonefish.container.init';
    const CONTAINER_SETUP = 'bonefish.container.setup';
    const REQUEST_INIT = 'bonefish.request.init';
    const COLLECTORS_INIT = 'bonefish.collectors.init';
    const REQUEST_BEFORE_HANDLE = 'bonefish.request.before';
    const REQUEST_HANDLE = 'bonefish.request.handle';
    const RESPONSE_BEFORE_SEND = 'bonefish.response.before';
    const RESPONSE_AFTER_SEND = 'bonefish.response.after';
}