<?php
/*!
 * Avalon
 * Copyright 2011-2015 Jack P.
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

use Avalon\Routing\Router;

/**
 * Shortcut for `htmlspecialchars`.
 *
 * @param string $string
 *
 * @return string
 */
function e($string)
{
    return htmlspecialchars($string);
}

/**
 * Shortcut for `Avalon\Language::translate()`.
 *
 * @see \Avalon\Language::translate()`
 */
function t($string, $replacements = [])
{
    return call_user_func_array(['\Avalon\Language', 'translate'], func_get_args());
}

/**
 * Shortcut for `Avalon\Language::date()`.
 *
 * @see \Avalon\Language::date()`
 */
function l($format, $timestamp = null)
{
    return call_user_func_array(['\Avalon\Language', 'date'], func_get_args());
}

/**
 * Shortcut for generating a route URL.
 *
 * @param string $route  route name
 * @param array  $tokens
 *
 * @return string
 */
function routePath($route, array $tokens = [])
{
    return Router::generatePath($route, $tokens);
}

/**
 * Shortcut for generating a route path.
 *
 * @see routePath()
 */
function routeUrl($route, array $tokens = [])
{
    return Router::generateUrl($route, $tokens);
}
