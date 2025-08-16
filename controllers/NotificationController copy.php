<?php

require_once '../models/Favorite.php';
require_once '../env.php';
// require '../validation/RequestValidation.php';

class NotificationController
{
    private Favorite $favorite;
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->favorite = new Favorite($pdo);
    }
    public function show($id)
    {
        $params = ['id' => $id];
        $result =  $this->favorite->select($params);
        dd($result);
        // dd($_SERVER['QUERY_STRING']);
    }
    public function registerFavorite()
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
            $submittedCode = $_GET['invite_code'] ?? null;
            $expectedCode = $_ENV['INVITE_CODE'] ?? '';

            if ($submittedCode !== $expectedCode) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid invitation code']);
                return;
            }

            $params = RequestValidation::validateQuery($_GET);
            // $pagination = RequestValidation::validatePagination($_GET);
            // dd($params);
            $filledCount = 0;
            foreach ($params as $value) {
                if (!empty($value)) {
                    $filledCount++;
                }
            }

            if ($filledCount < 2 || !$params['email']) {
                http_response_code(422);
                echo json_encode(['error' => 'At least two fields plus email must be filled to register a favorite']);
                return;
            }
            $this->favorite->insertInDb($params);


            $response = [
                'added favorite' => $params,
                'data' => 'success'
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
