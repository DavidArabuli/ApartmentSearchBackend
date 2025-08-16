<?php

require_once '../models/Favorite.php';
require_once '../vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class FavoriteController
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
        $result = $this->favorite->select($params);
        dd($result);
    }

    public function registerFavorite()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header('Content-Type: application/json; charset=utf-8');

        try {

            // Limit query string length
            $queryString = $_SERVER['QUERY_STRING'] ?? '';
            if (strlen($queryString) > 200) {
                http_response_code(400);
                echo json_encode(['error' => 'Query string too long']);
                exit;
            }

            // Invitation code check
            $submittedCode = $_POST['invite_code'] ?? null;
            $expectedCode = $_ENV['INVITE_CODE'] ?? '';
            if ($submittedCode !== $expectedCode) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid invitation code']);
                return;
            }

            // Email and confirmation check
            $email = $_POST['email'] ?? '';
            $emailConfirmation = $_POST['email_confirmation'] ?? '';

            if (empty($email) || empty($emailConfirmation)) {
                http_response_code(422);
                echo json_encode(['error' => 'Email and email confirmation are required']);
                return;
            }

            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            $emailConfirmation = filter_var($emailConfirmation, FILTER_VALIDATE_EMAIL);

            if (!$email || !$emailConfirmation) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid email format']);
                return;
            }

            if ($email !== $emailConfirmation) {
                http_response_code(422);
                echo json_encode(['error' => 'Emails do not match']);
                return;
            }

            // Validate other query params
            $params = RequestValidation::validateQuery($_POST);
            // $params['email'] = $email;

            // Check at least two other fields are filled + email
            // $filledCount = 0;
            // foreach ($params as $key => $value) {
            //     if (!empty($value) && $key !== 'email') {
            //         $filledCount++;
            //     }
            // }
            error_log(print_r($params, true));
            // if ($filledCount < 2) {
            //     http_response_code(422);
            //     echo json_encode(['error' => 'At least two other fields plus email must be filled to register a favorite']);
            //     return;
            // }

            // Save favorite
            // dd($params);
            $this->favorite->insertInDb($params);

            echo json_encode([
                'added favorite' => $params,
                'data' => 'success'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'failed to get data for your query',
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
