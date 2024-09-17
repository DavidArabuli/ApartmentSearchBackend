<?php
require_once 'dbh.inc.php';
class Analytics
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    // average m2 in database
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
        $averageM2 = [
            'averageM2' => $average
        ];
        return $averageM2;
    }
    public function averageM2Price()
    {
        $query = "SELECT AVG(cena / m2) AS averageM2Price FROM ss_rss_riga;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $averagePricePerSquareMeter = $result['averageM2Price'];
        return $averagePricePerSquareMeter;
    }
    // average appartement price in DB
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
        $averagePrice = [
            'averagePrice' => $average

        ];
        return $averagePrice;
    }
    public function highestPrice()
    {
        $query = "SELECT MAX(cena) as highestPrice FROM ss_rss_riga;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    public function lowestPrice()
    {
        $query = "SELECT MIN(cena) as lowestPrice FROM ss_rss_riga;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;
    }
    public function listSalesDistrict()
    {
        $query = "SELECT pagasts, COUNT(*) AS offer_count
             FROM ss_rss_riga
             GROUP BY pagasts
             ORDER BY offer_count DESC;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
    public function lowestAveragePriceDistrict()
    {
        $query = "SELECT pagasts, AVG(cena) AS average_price
             FROM ss_rss_riga
             GROUP BY pagasts
             ORDER BY average_price ASC;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
    public function averageM2PriceByDistrict()
    {
        $query = "SELECT pagasts, AVG(cena / NULLIF(m2, 0)) AS average_m2_price_by_district
        FROM ss_rss_riga
        WHERE m2 > 0
        GROUP BY pagasts
        ORDER BY average_m2_price_by_district ASC;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $averageM2PriceByDistrict = $result;
        return $averageM2PriceByDistrict;
    }
}
