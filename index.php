<?php
require_once __DIR__."/vendor/autoload.php";

use Core\Application;

try {
    $app = new Application([], false);
} catch (Exception $e) {
    die($e->getMessage());
}

$app->run();
die();
