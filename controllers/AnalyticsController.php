<?php
require '../models/Analytics.php';
require '../services/Cache.php';
require_once '../config/dbh.inc.php';

class AnalyticsController
{

    private Analytics $analytics;
    private Cache $cache;

    public function __construct(PDO $pdo)
    {
        $this->analytics = new Analytics($pdo);
        $this->cache = new Cache();
    }
    public function show()
    {

        $cacheKey = 'analytics_data';
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            $this->sendJson($cached);
            return;
        }
        $data = [
            'averageM2' => $this->analytics->averageM2(),
            'averagePrice' =>  $this->analytics->averagePrice(),
            'highestPrice' =>  $this->analytics->highestPrice(),
            'lowestPrice' =>  $this->analytics->lowestPrice(),
            'averageM2Price' => ['averageM2Price' => round($this->analytics->averageM2Price(), 2)],
            'mostSalesDistrict' =>  $this->analytics->listSalesDistrict(),
            'lowestAveragePriceDistrict' =>  $this->analytics->lowestAveragePriceDistrict(),
            'averageM2PriceByDistrict' => $this->analytics->averageM2PriceByDistrict()
        ];
        $this->cache->put($cacheKey, $data);
        $this->sendJson($data);
    }
    private function sendJson(array $data)
    {

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header('Content-Type: application/json');

        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
