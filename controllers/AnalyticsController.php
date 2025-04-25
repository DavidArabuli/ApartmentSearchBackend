<?php
require '../models/Analytics.php';

class AnalyticsController
{

    private Analytics $analytics;

    public function __construct(PDO $pdo)
    {
        $this->analytics = new Analytics($pdo);
    }
    public function show()
    {
        // $averageM2 = $this->analytics->averageM2();
        // $averagePrice = $this->analytics->averagePrice();
        // $highestPrice = $this->analytics->highestPrice();
        // $lowestPrice = $this->analytics->lowestPrice();
        // $averageM2Price = ['averageM2Price' => round($this->analytics->averageM2Price(), 2)];
        // $mostSalesDistrict = $this->analytics->listSalesDistrict();
        // $lowestAveragePriceDistrict = $this->analytics->lowestAveragePriceDistrict();
        // $averageM2PriceByDistrict = $this->analytics->averageM2PriceByDistrict();

        $data = [
            'averageM2' => $this->analytics->averageM2(),
            'averagePrice' =>  $this->analytics->averagePrice(),
            'highestPrice' =>  $this->analytics->highestPrice(),
            'lowestPrice' =>  $this->analytics->lowestPrice(),
            'averageM2Price' =>  ['averageM2Price' => round($this->analytics->averageM2Price(), 2)],
            'mostSalesDistrict' =>  $this->analytics->listSalesDistrict(),
            'lowestAveragePriceDistrict' =>  $this->analytics->lowestAveragePriceDistrict(),
            'averageM2PriceByDistrict' => $this->analytics->averageM2PriceByDistrict()
        ];

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header('Content-Type: application/json');

        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
