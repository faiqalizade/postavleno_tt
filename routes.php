<?php

use Core\Router;
use Core\Application;

Router::get('/api/{driver}', function ($driver) {
    return (new \Core\Driver(Application::$config['drivers'][$driver], Application::$config['parameters'][$driver]))->all();
});

Router::delete('/api/{driver}/{key}', function ($driver, $key) {
    return (new \Core\Driver(Application::$config['drivers'][$driver], Application::$config['parameters'][$driver]))->delete($key);
});
