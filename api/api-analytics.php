<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$cacheFile = 'cache/analytics.json';
$cacheTime = 900; // 15 minutes 

require_once '../analytics.php';

if (file_exists($cacheFile)) {


    // Serve cached data if the cache is still valid
    if ((time() - filemtime($cacheFile)) < $cacheTime) {

        $data = json_decode(file_get_contents($cacheFile), true);
        echo json_encode($data);
        exit;
    } else {
        echo "Cache expired. Recalculating data.\n";
    }
} else {
    echo "Cache file does not exist. Recalculating data.\n";
}

// Recalculate data if cache does not exist or has expired
$analyze = new Analytics($pdo);
$averageM2 = $analyze->averageM2();
$averagePrice = $analyze->averagePrice();
$highestPrice = $analyze->highestPrice();
$lowestPrice = $analyze->lowestPrice();
$averageM2Price = ['averageM2Price' => round($analyze->averageM2Price(), 2)];
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

// Save recalculated data to the cache
file_put_contents($cacheFile, json_encode($data));

echo json_encode($data);
