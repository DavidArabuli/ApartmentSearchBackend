<?php
require_once 'dbh.inc.php';
class Analytics
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function averageM2()
    {
        $query = "SELECT m2 FROM ss_rss_riga;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $values  = array_column($results, 'm2');
        $sum = array_sum($values);
        $count = count($values);
        $average = round($sum / $count, 2);
        return $average;
    }
    public function averagePrice()
    {
        $query = "SELECT cena FROM ss_rss_riga;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $values  = array_column($results, 'cena');
        $sum = array_sum($values);
        $count = count($values);
        $average = round($sum / $count, 2);
        return $average;
    }
}
