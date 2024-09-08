<?php

header("Access-Control-Allow-Origin: *");


header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


require_once '../districts.php';
$districts = new Districts($pdo);
$uniqueDistricts = $districts->getDistricts();
echo json_encode($uniqueDistricts); 

// echo count($results);
// echo '<pre>';
// var_dump($districts);
// echo '</pre>';