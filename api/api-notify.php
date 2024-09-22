<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

echo 'Request method: ' . $_SERVER['REQUEST_METHOD'] . "\n";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log all incoming POST data
    var_dump($_POST);

    $params['istabas'] = isset($_POST['istabas']) ? filter_var($_POST['istabas'], FILTER_VALIDATE_INT) : null;

    $params['pagasts'] = isset($_POST['pagasts']) ? htmlspecialchars($_POST['pagasts'], ENT_QUOTES, 'UTF-8') : null;

    $params['m2_min'] = isset($_POST['m2_min']) ? filter_var($_POST['m2_min'], FILTER_VALIDATE_INT) : null;

    $params['m2_max'] = isset($_POST['m2_max']) ? filter_var($_POST['m2_max'], FILTER_VALIDATE_INT) : null;

    $params['cena_min'] = isset($_POST['cena_min']) ? filter_var($_POST['cena_min'], FILTER_VALIDATE_INT) : null;

    $params['cena_max'] = isset($_POST['cena_max']) ? filter_var($_POST['cena_max'], FILTER_VALIDATE_INT) : null;

    $params['stavs_min'] = isset($_POST['stavs_min']) ? filter_var($_POST['stavs_min'], FILTER_VALIDATE_INT) : null;

    $params['stavs_max'] = isset($_POST['stavs_max']) ? filter_var($_POST['stavs_max'], FILTER_VALIDATE_INT) : null;

    if (!empty($_POST['email']) && !empty($_POST['email_confirmation'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $email_confirmation = filter_var($_POST['email_confirmation'], FILTER_VALIDATE_EMAIL);
        if (!$email || !$email_confirmation) {
            echo json_encode(['error' => 'Invalid email forma']);
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
}
