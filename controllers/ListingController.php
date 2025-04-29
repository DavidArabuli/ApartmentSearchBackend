<?php
require '../validation/RequestValidation.php';
class ListingController
{

    private Listing $listing;
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->listing = new Listing($pdo);
    }

    public function index()
    {

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header('Content-Type: application/json; charset=utf-8');
        try {
            //code...
            $queryString = $_SERVER['QUERY_STRING'] ?? '';
            if (strlen($queryString) > 200) {
                http_response_code(400);
                echo json_encode(['error' => 'Query string too long']);
                exit;
            }

            $params = RequestValidation::validateQuery($_GET);
            $pagination = RequestValidation::validatePagination($_GET);


            $mainQuery = $this->listing->select($params);
            // dd($mainQuery);
            $totalResults = count($mainQuery);
            $totalPages = ceil($totalResults / $pagination['page_limit']);
            $offset = ($pagination['page'] - 1) * $pagination['page_limit'];

            $selectedPart = array_slice($mainQuery, $offset, $pagination['page_limit']);
            $response = [
                'data' => $selectedPart,
                'pagination' => [
                    'current_page' => $pagination['page'],
                    'page_limit' => $pagination['page_limit'],
                    'total_results' => $totalResults,
                    'total_pages' => $totalPages
                ]
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'failed to get data for your query',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
