<?php

header("Access-Control-Allow-Origin: *");


header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


require_once '../analytics.php';

$analyze = new Analytics($pdo);
$averageM2 = $analyze->averageM2();
$averagePrice = $analyze->averagePrice();
// echo count($results);
// echo '<pre>';
// var_dump($results);
// echo '</pre>';

echo json_encode($averageM2);
echo json_encode($averagePrice);
