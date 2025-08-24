<?php

require_once __DIR__ . '/../models/Favorite.php';
require_once __DIR__ . '/../vendor/autoload.php';

class FavoriteController
{
    private Favorite $favorite;
    private PDO $pdo;

    // to do
    // Rate limiting 
    // private array $emailAttempts = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->favorite = new Favorite($pdo);
    }

    public function registerFavorite()
    {
        try {
            // Parse JSON or form-data
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                $data = $_POST;
            }

            // Validate invite code
            $submittedCode = $data['invite_code'] ?? null;
            $expectedCode = $_ENV['INVITE_CODE'] ?? '';
            if ($submittedCode !== $expectedCode) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid invitation code']);
                return;
            }

            // Email validation
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


            // to do

            // Rate limiting: max 5 submissions per hour per email
            // $now = time();
            // if (!isset($this->emailAttempts[$email])) {
            //     $this->emailAttempts[$email] = [];
            // }
            // remove old attempts
            // $this->emailAttempts[$email] = array_filter(
            //     $this->emailAttempts[$email],
            //     fn($timestamp) => ($now - $timestamp) < 3600
            // );
            // if (count($this->emailAttempts[$email]) >= 5) {
            //     http_response_code(429);
            //     echo json_encode(['error' => 'Too many submissions for this email. Try again later.']);
            //     return;
            // }
            // $this->emailAttempts[$email][] = $now;

            // Filter other params
            $params = [];

            // Rooms
            $params['rooms'] = isset($data['rooms']) ? filter_var($data['rooms'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 0, 'max_range' => 1000]
            ]) : null;
            if ($params['rooms'] === false) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid rooms value']);
                return;
            }

            // District
            $params['district'] = isset($data['district']) ? htmlspecialchars(substr($data['district'], 0, 100), ENT_QUOTES, 'UTF-8') : null;

            // m2_min
            $params['m2_min'] = isset($data['m2_min']) ? filter_var($data['m2_min'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 0, 'max_range' => 10000]
            ]) : null;
            if ($params['m2_min'] === false) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid m2_min value']);
                return;
            }

            // m2_max
            $params['m2_max'] = isset($data['m2_max']) ? filter_var($data['m2_max'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 0, 'max_range' => 10000]
            ]) : null;
            if ($params['m2_max'] === false) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid m2_max value']);
                return;
            }

            // price_min
            $params['price_min'] = isset($data['price_min']) ? filter_var($data['price_min'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 0, 'max_range' => 100000000]
            ]) : null;
            if ($params['price_min'] === false) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid price_min value']);
                return;
            }

            // price_max
            $params['price_max'] = isset($data['price_max']) ? filter_var($data['price_max'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 0, 'max_range' => 100000000]
            ]) : null;
            if ($params['price_max'] === false) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid price_max value']);
                return;
            }

            // floor_min
            $params['floor_min'] = isset($data['floor_min']) ? filter_var($data['floor_min'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 0, 'max_range' => 200]
            ]) : null;
            if ($params['floor_min'] === false) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid floor_min value']);
                return;
            }

            // floor_max
            $params['floor_max'] = isset($data['floor_max']) ? filter_var($data['floor_max'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 0, 'max_range' => 200]
            ]) : null;
            if ($params['floor_max'] === false) {
                http_response_code(422);
                echo json_encode(['error' => 'Invalid floor_max value']);
                return;
            }

            $params['email'] = $email;
            // optional to do
            // Require at least 2 non-email fields
            // $filledCount = 0;
            // foreach ($params as $key => $value) {
            //     if (!empty($value) && $key !== 'email') {
            //         $filledCount++;
            //     }
            // }
            // if ($filledCount < 2) {
            //     http_response_code(422);
            //     echo json_encode(['error' => 'At least two other fields plus email must be filled to register a favorite']);
            //     return;
            // }

            // Insert into DB
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
