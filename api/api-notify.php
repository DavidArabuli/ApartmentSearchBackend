<?php

require_once '../cors.php';
require_once '../controllers/FavoriteController.php';
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new FavoriteController($pdo);
    $controller->registerFavorite();
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
// require_once 'cors.php';
// header("Access-Control-Allow-Origin: http://localhost:5173");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json");

// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(204);
//     exit;
// }

// require_once '../controllers/FavoriteController.php';
// require_once '../config/db.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $controller = new FavoriteController($pdo);
//     $controller->registerFavorite();
//     exit;
// }

// http_response_code(405);
// echo json_encode(['error' => 'Method not allowed']);
