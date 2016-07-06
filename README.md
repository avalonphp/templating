# Avalon - Templating Package

Fast and easy templating.

## Installation

This package can be installed via composer:

    composer require avalon/templating

## Engines

There are two templating engines available for use:

### PHP

Basically the same as `include 'myfile.php';` but returns the rendered view as
opposed to straight up outputting it.

### PHP Extended

Allows for extending other views and defining content blocks.

````php
<!-- layouts/default.phtml -->
<h1>My Layout</h1>
<?=$this->getSection('content')?>
````

````php
<!-- my_view.phtml -->
<?php $this->extend('layouts/default.phtml'); ?>

<?php $this->startSection('pageTitle', 'My Page'); ?>

<?php $this->startSection('content'); ?>
View content here
<?php $this->endSection(); ?>
````

## Usage

This example uses the PHP Extended engine.

````php
use Avalon\Templating\View;
use Avalon\Templating\View\Engines\PhpExtended;

// Instantiate the engine
$engine = new PhpExtended;

// Set it as the engine for the `View` singleton to use.
View::setEngine($engine);

// Add some paths to search for views in
View::addPath('/path/to/views/directory');
View::addPath('/path/to/another/views/directory');

// Render a view
View::render('my_view.phtml');

// Variables can also be passed to views like so:
View::render('my_view.phtml', ['variableName' => 'Variable value']);
````
