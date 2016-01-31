<?php
/*!
 * Avalon
 * Copyright 2011-2016 Jack P.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Avalon\Templating;

/**
 * The "Safe Object" class, used to get escaped values from objects in views.
 *
 * @package Avalon\Templating
 * @author Jack P.
 * @since 4.0.0
 */
class SafeObject
{
    /**
     * @var object
     */
    protected $object;

    /**
     * @param object $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @param string $var
     *
     * @return mixed
     */
    public function __get($var)
    {
        return View::escape($this->object->{$var});
    }

    /**
     * @param string $var
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, array $args = [])
    {
        return View::escape(call_user_func_array([$this->object, $method], $args));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return method_exists($this->object, '__toString')
                ? $this->object->__toString()
                : '[object: ' . get_called_class() . ']';
    }
}
