<?php

require '../core/Router.php';

$router = new Router;

$routes = require '../views/routes.php';
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
