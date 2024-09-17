<?php
require_once '../sanitizeData.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


require_once '../analytics.php';

$analyze = new Analytics($pdo);
$averageM2 = $analyze->averageM2();
$averagePrice = $analyze->averagePrice();
$highestPrice = $analyze->highestPrice();
$lowestPrice = $analyze->lowestPrice();
$averageM2Price = [
    'averageM2Price' => round($analyze->averageM2Price(), 2)
];
$mostSalesDistrict = $analyze->listSalesDistrict();
$lowestAveragePriceDistrict = $analyze->lowestAveragePriceDistrict();
$averageM2PriceByDistrict = $analyze->averageM2PriceByDistrict();


$data = [
    'averageM2' => $averageM2,
    'averagePrice' =>  $averagePrice,
    'highestPrice' =>  $highestPrice,
    'lowestPrice' =>  $lowestPrice,
    'averageM2Price' =>  $averageM2Price,
    'mostSalesDistrict' =>  $mostSalesDistrict,
    'lowestAveragePriceDistrict' =>  $lowestAveragePriceDistrict,
    'averageM2PriceByDistrict' => $averageM2PriceByDistrict
];
echo json_encode($data);
// echo '<pre>';
// var_dump($data);

// echo '</pre>';
// echo json_encode($averagePrice);



// echo '<pre>';
// var_dump($averageM2);
// var_dump($averagePrice);
// var_dump($highestPrice);
// var_dump($lowestPrice);
// var_dump($averageM2Price);
// var_dump($mostSalesDistrict);
// var_dump($lowestAveragePriceDistrict);
// echo '</pre>';