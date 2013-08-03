<?php
/**
 * Load composer autoloader
 */
$loader = require __DIR__ . "/../vendor/autoload.php";

/**
 * Setup library directory
 */
$loader->add('Zenith\\SOAP\\', __DIR__ . '/../src/');