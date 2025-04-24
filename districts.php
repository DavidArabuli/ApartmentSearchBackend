<?php
require_once 'dbh.inc.php';
class Districts
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getDistricts()
    {
        $query = "SELECT district FROM listings;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pagastsArray = array_column($results, 'district');
        $uniqueResults = array_unique($pagastsArray);



        return $uniqueResults;
    }
}
// $uniqueResults = array_unique($results);
// $values  = array_column($results, 'pagasts');
        // echo '<pre>';
        // var_dump($results);
        // echo '</pre>';