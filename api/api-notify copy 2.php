<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight CORS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        'rooms' => filter_input(INPUT_POST, 'rooms', FILTER_VALIDATE_INT),
        'district' => isset($_POST['district']) ? htmlspecialchars($_POST['district'], ENT_QUOTES, 'UTF-8') : null,
        'm2_min'  => filter_input(INPUT_POST, 'm2_min', FILTER_VALIDATE_INT),
        'm2_max'  => filter_input(INPUT_POST, 'm2_max', FILTER_VALIDATE_INT),
        'price_min' => filter_input(INPUT_POST, 'price_min', FILTER_VALIDATE_INT),
        'price_max' => filter_input(INPUT_POST, 'price_max', FILTER_VALIDATE_INT),
        'floor_min' => filter_input(INPUT_POST, 'floor_min', FILTER_VALIDATE_INT),
        'floor_max' => filter_input(INPUT_POST, 'floor_max', FILTER_VALIDATE_INT),
    ];


    if (!empty($_POST['email']) && !empty($_POST['email_confirmation'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $email_confirmation = filter_var($_POST['email_confirmation'], FILTER_VALIDATE_EMAIL);
        if (!$email || !$email_confirmation) {
            echo json_encode(['error' => 'Invalid email format']);
            exit;
        }
        if ($email !== $email_confirmation) {
            echo json_encode(['error' => 'Emails do not match']);
            exit;
        }
        $params['email'] = $email;
    } else {
        echo json_encode(['error' => 'Email and email confirmation is required!']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $params]);
    exit;
}

echo json_encode(['error' => 'Invalid request method']);
