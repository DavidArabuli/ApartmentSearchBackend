<?php
require_once '../controllers/DistrictsController.php';
require_once '../controllers/ListingController.php';
require_once '../controllers/AnalyticsController.php';
require_once '../controllers/NotificationController.php';
require_once '../controllers/FeedController.php';
require_once '../config/dbh.inc.php';

$router->get('/', '/index.php');


$router->get('/api/districts', function () use ($pdo) {
    $ApiController = new DistrictsController($pdo);
    $ApiController->show();
});
$router->get('/api/analytics', function () use ($pdo) {
    $ApiController = new AnalyticsController($pdo);
    $ApiController->show();
});

$router->get('/feed', function () use ($pdo) {
    $controller = new FeedController($pdo);
    $controller->updateFeed('../data.xml');
});
$router->get('/api/listings', function () use ($pdo) {
    $controller = new ListingController($pdo);
    $controller->index();
});
$router->get('/api/notify', function () use ($pdo) {
    $controller = new NotificationController($pdo);
    $controller->registerFavorite();
});
$router->get('/api/notify/:id', function ($id) use ($pdo) {
    $controller = new NotificationController($pdo);
    $controller->show($id);
});

// $router->get("/api/notify", function () use ($pdo) {
//     $uri = $_SERVER['REQUEST_URI'];
//     $parts = explode('/', trim($uri, '/'));
//     $id = $parts[2] ?? null;

//     if ($id && is_numeric($id)) {
//         $controller = new NotificationController($pdo);
//         $controller->show($id);
//         return;
//     }

//     // No ID? Handle favorite registration
//     $controller = new NotificationController($pdo);
//     $controller->registerFavorite();
// });

// $router->get("/api/notify", function ($id) use ($pdo) {
//     $uri = $_SERVER['REQUEST_URI'];
//     $parts = explode('/', trim($uri, '/'));
//     $id = $parts[2] ?? null;

//     if ($id && is_numeric($id)) {
//         $controller = new NotificationController($pdo);
//         $controller->show($id);
//         return;
//     } else {
//         http_response_code(400);
//         json_encode([
//             'error' => 'missing or invalid ID'
//         ]);
//     }
// });



// if($_SERVER['QUERY_STRING'] === 'id'){

//     $router->get("/api/notify/{$_SERVER['QUERY_STRING']}", function () use ($pdo) {
        
//         $controller = new NotificationController($pdo);
//         $controller->registerFavorite();
//     });
// }
