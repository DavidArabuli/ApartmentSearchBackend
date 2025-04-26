<?php

class Listing
{
    private $table;
    private $data;
    private $pdo;



    public function __construct(array $data, PDO $pdo, string $table = 'listings')
    {
        $this->data = $data;
        $this->pdo = $pdo;
        $this->table = $table;
    }
    public function mainSelector(array $params)
    {


        try {
            $baseSelectQuery = "SELECT * FROM {$this->table} ";
            $conditions = [];
            $bindings = [];


            if (!empty($params['istabas'])) {
                $conditions[] = "istabas = :istabas";
                $bindings[':istabas'] = $params['istabas'];
            }
            if (!empty($params['pagasts'])) {
                $conditions[] = "pagasts = :pagasts";
                $bindings[':pagasts'] = $params['pagasts'];
            }
            if (!empty($params['m2_min'])) {
                $conditions[] = "m2 > :m2_min";
                $bindings[':m2_min'] = $params['m2_min'];
            }
            if (!empty($params['m2_max'])) {
                $conditions[] = "m2 < :m2_max";
                $bindings[':m2_max'] = $params['m2_max'];
            }
            if (!empty($params['cena_min'])) {
                $cena_min = preg_replace('/[^\d.]/', '', $params['cena_min']);
                $conditions[] = "cena > :cena_min";
                $bindings[':cena_min'] = $cena_min;
            }
            if (!empty($params['cena_max'])) {
                $cena_max = preg_replace('/[^\d.]/', '', $params['cena_max']);
                $conditions[] = "cena < :cena_max";
                $bindings[':cena_max'] = $cena_max;
            }
            if (!empty($params['stavs_min'])) {
                $conditions[] = "substr(stavs, 1, instr(stavs, '/') - 1) >= :stavs_min";
                $bindings[':stavs_min'] = $params['stavs_min'];
            }
            if (!empty($params['stavs_max'])) {
                $conditions[] = "substr(stavs, 1, instr(stavs, '/') - 1) <= :stavs_max";
                $bindings[':stavs_max'] = $params['stavs_max'];
            }


            if (count($conditions) > 0) {
                $baseSelectQuery .= ' WHERE ' . implode(' AND ', $conditions);
            }

            // Prepare and execute the query
            $stmt = $this->pdo->prepare($baseSelectQuery);
            $stmt->execute($bindings);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            // $e->getMessage();
            die('Error creating table: ' . $e->getMessage());
        }
    }

    public function insertInDb()
    {
        // require_once "dbh.inc.php";
        require_once __DIR__ . '/../config/dbh.inc.php';

        // global $pdo;
        try {

            $query = "INSERT INTO " . $this->table . " (title, imgSrc, district, floor, series, price, m2, rooms, street, pubDate, link, hash) VALUES (:title, :imgSrc, :pagasts, :stavs, :serija, :cena, :m2, :istabas, :iela, :pubDate, :link, :hash);";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(":title", $this->data['title']);
            $stmt->bindParam(":imgSrc", $this->data['imgSrc']);
            $stmt->bindParam(":pagasts", $this->data['pagasts']);
            $stmt->bindParam(":stavs", $this->data['stavs']);
            $stmt->bindParam(":serija", $this->data['serija']);
            $stmt->bindParam(":cena", $this->data['cena']);
            $stmt->bindParam(":m2", $this->data['m2']);
            $stmt->bindParam(":istabas", $this->data['istabas']);
            $stmt->bindParam(":iela", $this->data['iela']);
            $stmt->bindParam(":pubDate", $this->data['pubDate']);
            $stmt->bindParam(":link", $this->data['link']);
            $stmt->bindParam(":hash", $this->data['hash']);
            $stmt->execute();

            // $pdo = null;
            // $stmt = null;
            // die();
        } catch (PDOException $e) {
            $errorCode = $e->getCode();

            if ($errorCode === '23000' || $errorCode === 23000) {
                echo 'Entry with this hash already exists in DB';
            } else {

                die("Query failed: " . $e->getMessage());
            }
        }
    }
}
