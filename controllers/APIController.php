<?php


require_once '../config/dbh.inc.php';

// echo 'hey from API controller';
class APIController
{

    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAnalytics()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header('Content-Type: application/json; charset=utf-8');

        try {
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'failed to fetch analytics',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function getDistricts()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header('Content-Type: application/json; charset=utf-8');
        try {

            // echo 'hey from districts';
            $query = "SELECT district FROM listings;";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $districtArray = array_column($results, 'district');
            $uniqueResults = array_unique($districtArray);


            // print_r($uniqueResults);
            // echo json_encode($uniqueResults);
            echo json_encode([
                'status' => 'success',
                'data' => $uniqueResults
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            return $uniqueResults;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'failed to fetch districts',
                'error' => $e->getMessage()

            ]);
        }
    }
}
