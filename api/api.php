<?php

header("Access-Control-Allow-Origin: *");


header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


require_once '../selector.php';
// require_once 'dbh.inc.php';

$params = [];
$pagination = [
    'page_limit' => 10,  // Default page limit
    'page' => 1          // Default page number
];

if (isset($_GET['istabas'])) {
    $params['istabas'] = $_GET['istabas'];
}
if (isset($_GET['pagasts'])) {
    $params['pagasts'] = $_GET['pagasts'];
}
if (isset($_GET['m2_min'])) {
    $params['m2_min'] = $_GET['m2_min'];
}
if (isset($_GET['m2_max'])) {
    $params['m2_max'] = $_GET['m2_max'];
}
if (isset($_GET['cena_min'])) {
    $params['cena_min'] = $_GET['cena_min'];
}
if (isset($_GET['cena_max'])) {
    $params['cena_max'] = $_GET['cena_max'];
}
if (isset($_GET['stavs_min'])) {
    $params['stavs_min'] = $_GET['stavs_min'];
}
if (isset($_GET['stavs_max'])) {
    $params['stavs_max'] = $_GET['stavs_max'];
}
if (isset($_GET['page_limit'])) {
    $pagination['page_limit'] = $_GET['page_limit'];
}
if (isset($_GET['page'])) {
    $pagination['page'] = $_GET['page'];
}

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
