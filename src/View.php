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

use Exception;
use Avalon\Templating\EngineInterface;

/**
 * View renderer.
 *
 * @package Avalon\Templating
 * @author Jack P.
 * @since 0.1.0
 */
class View
{
    /**
     * @var EngineInterface
     */
    protected static $engine;

    /**
     * Configuration block.
     *
     * @param callable $block
     */
    public static function configure(callable $block)
    {
        $block(new static);
    }

    /**
     * Returns the rendering engine;
     *
     * @return EngineInterface
     */
    public static function engine()
    {
        return static::$engine;
    }

    /**
     * Sets the rendering engine.
     *
     * @param EngineInterface $engine
     */
    public static function setEngine(EngineInterface $engine)
    {
        static::$engine = $engine;
    }

    /**
     * Adds a global variable for all templates.
     *
     * @param string $name
     * @param mixed  $value
     */
    public static function addGlobal($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $variable => $value) {
                static::addGlobal($variable, $value);
            }
        } else {
            static::$engine->addGlobal($name, $value);
        }
    }

    /**
     * Adds a template path to search in.
     *
     * @param string|array $path
     */
    public static function addPath($path, $prepend = false)
    {
        static::$engine->addPath($path, $prepend);
    }

    /**
     * @param string $template
     * @param array  $locals
     *
     * @return string
     */
    public static function render($template, array $locals = [])
    {
        return static::$engine->render($template, $locals);
    }

    /**
     * Escape the given value. If the value is an object or an array of an object,
     * wrap it in the `SafeObject` class.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function escape($value)
    {
        if (is_string($value)) {
            $value = htmlspecialchars($value);
        } elseif (is_array($value)) {
            $value = array_map([get_called_class(), 'escape'], $value);
        } elseif (is_object($value)) {
            $value = new SafeObject($value);
        }

        return $value;
    }

    /**
     * Checks if the template exists.
     *
     * @param string $template
     *
     * @return bool
     */
    public static function exists($template)
    {
        return static::$engine->exists($template);
    }

    /**
     * Load the shortcut functions file.
     */
    public static function loadFunctions()
    {
        require __DIR__ . '/functions.php';
    }
}
