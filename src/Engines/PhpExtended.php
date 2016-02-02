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

namespace Avalon\Templating\Engines;

use Exception;
use Avalon\Templating\View;

/**
 * Extended PHP view rendering engine.
 *
 * This allows pure PHP views to extend views.
 *
 * @package Avalon\Templating
 * @author Jack P.
 * @copyright (c) 2016 Jack P.
 * @since 2.0.0
 */
class PhpExtended extends PhpEngine
{
    /**
     * Queue of views being rendered.
     */
    protected $renderingViews = [];

    /**
     * Sections of code.
     *
     * @var array
     */
    protected $sections = [];

    /**
     * Section stack.
     *
     * @var array
     */
    protected $sectionStack = [];

    /**
     * Queue of views being extended.
     *
     * @var array
     */
    protected $extends;

    /**
     * @return string
     */
    public function name()
    {
        return 'PhpExtended';
    }

    /**
     * @param string $template
     * @param array  $locals
     *
     * @return string
     */
    public function render($_template, array $_locals = [])
    {
        // Add view to queue
        $this->renderingViews[] = $_template;

        $_templatePath = $this->find($_template);

        if (!$_templatePath) {
            $_paths = implode(', ', $this->paths);
            throw new Exception("Unable to find template [$_template] searched in [{$_paths}]");
        }

        // View variables
        $_variables = $_locals + $this->globals;

        if ($this->escapeVariables) {
            foreach ($_variables as $__name => $__value) {
                $$__name = $__name == 'content' ? $__value : View::escape($__value);
            }
        } else {
            extract($_variables);
        }

        unset($_paths, $_variables, $_locals, $__name, $__value);

        ob_start();
        include($_templatePath);
        $renderedView = ob_get_clean();

        // Get view
        $renderingView = array_pop($this->renderingViews);

        // If the view is extending another, render the extended view.
        if (isset($this->extends[$renderingView])) {
            $_extends = $this->extends[$renderingView];
            unset($this->extends[$renderingView]);

            $renderedView = $this->render($_extends, ['content' => $renderedView]);
        }

        return $renderedView;
    }

    /**
     * Extend a view.
     *
     * @param string $view
     */
    protected function extend($view)
    {
        // Add view to extends queue
        $this->extends[end($this->renderingViews)] = $view;
    }

    /**
     * Check if the specified section exists.
     *
     * @param string $name
     *
     * @return boolean
     */
    protected function hasSection($name)
    {
        return isset($this->sections[$name]);
    }

    /**
     * Get the content for the specified section or use the fallback.
     *
     * @param string $name
     * @param mixed  $fallback
     *
     * @return mixed
     */
    protected function getSection($name, $fallback = null)
    {
        $section = isset($this->sections[$name]) ? $this->sections[$name] : $fallback;
        unset($this->sections[$name]);
        return $section;
    }

    /**
     * Start a section with the specified name.
     *
     * @param string $name
     * @param mixed  $content section content if not using the block style.
     */
    protected function startSection($name, $content = null)
    {
        if ($content) {
            $this->sections[$name] = $content;
        } else {
            $this->sectionStack[] = $name;
            ob_start();
        }
    }

    /**
     * End section.
     */
    protected function endSection()
    {
        $section = array_pop($this->sectionStack);
        $this->sections[$section] = ob_get_clean();
    }
}
