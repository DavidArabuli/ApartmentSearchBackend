<?php
require_once '../controllers/APIController.php';
require_once '../controllers/AnalyticsController.php';
require_once '../controllers/FeedController.php';
require_once '../config/dbh.inc.php';

$router->get('/', '/index.php');
// $router->get('/feed', '/FeedController.php');


$router->get('/api/districts', function () use ($pdo) {
    $ApiController = new APIController($pdo);
    $ApiController->getDistricts();
});
$router->get('/api/analytics', function () use ($pdo) {
    $ApiController = new AnalyticsController($pdo);
    $ApiController->show();
});

$router->get('/feed', function () use ($pdo) {
    $controller = new FeedController($pdo);
    $controller->updateFeed('../data.xml');
});
