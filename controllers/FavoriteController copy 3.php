<?php

require_once __DIR__ . '/../models/Favorite.php';
require_once __DIR__ . '/../vendor/autoload.php';

class FavoriteController
{
    private Favorite $favorite;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->favorite = new Favorite($pdo);
    }

    public function registerFavorite()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            // Parse input - support JSON and form-data
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                $data = $_POST; // fallback for form-data
            }

            // Email & invite validation
            $submittedCode = $data['invite_code'] ?? null;
            $expectedCode = $_ENV['INVITE_CODE'] ?? '';
            if ($submittedCode !== $expectedCode) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid invitation code']);
                return;
            }

            $email = $data['email'] ?? '';
            $emailConfirmation = $data['email_confirmation'] ?? '';

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

            // ✅ Your original params mapping logic
            $params = [
                'rooms'      => isset($data['rooms']) ? filter_var($data['rooms'], FILTER_VALIDATE_INT) : null,
                'district'   => isset($data['district']) ? htmlspecialchars($data['district'], ENT_QUOTES, 'UTF-8') : null,
                'm2_min'     => isset($data['m2_min']) ? filter_var($data['m2_min'], FILTER_VALIDATE_INT) : null,
                'm2_max'     => isset($data['m2_max']) ? filter_var($data['m2_max'], FILTER_VALIDATE_INT) : null,
                'price_min'  => isset($data['price_min']) ? filter_var($data['price_min'], FILTER_VALIDATE_INT) : null,
                'price_max'  => isset($data['price_max']) ? filter_var($data['price_max'], FILTER_VALIDATE_INT) : null,
                'floor_min'  => isset($data['floor_min']) ? filter_var($data['floor_min'], FILTER_VALIDATE_INT) : null,
                'floor_max'  => isset($data['floor_max']) ? filter_var($data['floor_max'], FILTER_VALIDATE_INT) : null,
                'email'      => $email
            ];

            // Require at least 2 other fields filled (excluding email)
            $filledCount = 0;
            foreach ($params as $key => $value) {
                if (!empty($value) && $key !== 'email') {
                    $filledCount++;
                }
            }
            if ($filledCount < 2) {
                http_response_code(422);
                echo json_encode(['error' => 'At least two other fields plus email must be filled to register a favorite']);
                return;
            }

            // Save to DB
            $this->favorite->insertInDb($params);

            echo json_encode([
                'status' => 'success',
                'added_favorite' => $params
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to save favorite',
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
