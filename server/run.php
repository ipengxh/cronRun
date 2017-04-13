<?php

/**
 * the server should run in cli mode.
 * administrator should disable web access to this script.
 */
if ('cli' !== php_sapi_name()) {
    echo "You should run this script on cli mode.";die(-1);
}

if (PHP_VERSION_ID < 70000) {
    echo "This script needs php for php 7.0 or higher.";die(-1);
}

/**
 * swoole based, thanks to Rango
 * @link https://github.com/swoole/swoole-src
 * @link http://www.swoole.com/
 */
if (!extension_loaded('swoole')) {
    echo "Extension swoole is required, but it's not loaded now.";die(-1);
}

define('BASE_PATH', dirname(__FILE__));

$configPath = BASE_PATH . '/config/';
$vendorPath = BASE_PATH . '/vendor/';

if (!file_exists($configPath . 'config.php')) {
    echo "Config file not found.";die(-1);
}

$config = require $configPath . 'config.php';
$swooleConfig = require $configPath . 'swoole.php';

require $vendorPath . 'server.php';

$server = new Server($config, $swooleConfig);
$server->run();
