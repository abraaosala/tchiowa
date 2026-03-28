<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->get('/', function () {
    return 'App was successfully reset!';
});
