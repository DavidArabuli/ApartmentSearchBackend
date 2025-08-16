<?php

class Analytics
{
    private $table;
    private $pdo;

    public function __construct(PDO $pdo, string $table = 'listings')
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }
    // average m2 in database
    public function averageM2()
    {
        $query = "SELECT m2 FROM {$this->table};";
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
        $query = "SELECT AVG(price / m2) AS averageM2Price FROM {$this->table};";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $averagePricePerSquareMeter = $result['averageM2Price'];
        return $averagePricePerSquareMeter;
    }
    // average apartement price in DB
    public function averagePrice()
    {
        $query = "SELECT price FROM {$this->table};";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $values  = array_column($results, 'price');
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
        $query = "SELECT MAX(price) as highestPrice FROM {$this->table};";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function lowestPrice()
    {
        $query = "SELECT MIN(price) as lowestPrice FROM {$this->table};";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // number of offers per district
    public function listSalesDistrict()
    {
        $query = "SELECT district, COUNT(*) AS offer_count
            FROM listings
            GROUP BY district
            ORDER BY offer_count DESC;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lowestAveragePriceDistrict()
    {
        $query = "SELECT district, AVG(price) AS average_price
            FROM {$this->table}
            GROUP BY district
            ORDER BY average_price ASC;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function averageM2PriceByDistrict()
    {
        $query = "SELECT district, AVG(price / NULLIF(m2, 0)) AS average_m2_price_by_district
        FROM {$this->table}
        WHERE m2 > 0
        GROUP BY district
        ORDER BY average_m2_price_by_district ASC;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $averageM2PriceByDistrict = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $averageM2PriceByDistrict;
    }
}
