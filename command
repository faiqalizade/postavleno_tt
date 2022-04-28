#!/opt/homebrew/bin/php
<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require 'vendor/autoload.php';
use Core\Application;

try {
    $app = new Application($argv, true);
} catch (Exception $e) {
    die($e->getMessage());
}

$app->run();
