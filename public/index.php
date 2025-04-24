<?php

require '../core/Router.php';

// function dd($data)
// {
//     echo '<pre>';
//     die(var_dump($data));
//     echo '</pre>';
// }
$router = new Router;

$routes = require '../views/routes.php';
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
