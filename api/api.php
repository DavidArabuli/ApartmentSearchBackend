<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once '../selector.php';
// require_once 'dbh.inc.php';

if (strlen($_SERVER['QUERY_STRING']) > 200) {
    http_response_code(400);
    echo json_encode(['error' => 'Query string too long']);
    exit;
}

$params = [];
$pagination = [
    'page_limit' => 2,  // Default page limit
    'page' => 1          // Default page number
];

// Sanitize and validate inputs
$params['istabas'] = isset($_GET['istabas']) ? filter_var($_GET['istabas'], FILTER_VALIDATE_INT) : null;

$params['pagasts'] = isset($_GET['pagasts']) ? htmlspecialchars($_GET['pagasts'], ENT_QUOTES, 'UTF-8') : null;

$params['m2_min'] = isset($_GET['m2_min']) ? filter_var($_GET['m2_min'], FILTER_VALIDATE_INT) : null;

$params['m2_max'] = isset($_GET['m2_max']) ? filter_var($_GET['m2_max'], FILTER_VALIDATE_INT) : null;

$params['cena_min'] = isset($_GET['cena_min']) ? filter_var($_GET['cena_min'], FILTER_VALIDATE_INT) : null;

$params['cena_max'] = isset($_GET['cena_max']) ? filter_var($_GET['cena_max'], FILTER_VALIDATE_INT) : null;

$params['stavs_min'] = isset($_GET['stavs_min']) ? filter_var($_GET['stavs_min'], FILTER_VALIDATE_INT) : null;

$params['stavs_max'] = isset($_GET['stavs_max']) ? filter_var($_GET['stavs_max'], FILTER_VALIDATE_INT) : null;

// Validate and sanitize 'page_limit' and 'page'
$pagination['page_limit'] = isset($_GET['page_limit']) ? (int)$_GET['page_limit'] : 4;
$pagination['page_limit'] = $pagination['page_limit'] > 0 && $pagination['page_limit'] <= 100 ? $pagination['page_limit'] : 2;

$pagination['page'] = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pagination['page'] = max(1, $pagination['page']);  // Ensure page is at least 1

$selection = new Selector($pdo);
$mainQuery = $selection->mainSelector($params);
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
echo json_encode($response);


// echo '<pre>';
// var_dump($params);
// var_dump($pagination);
// var_dump($selectedPart);
// echo '</pre>';
