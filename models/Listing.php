<?php

class Listing
{
    private $table;
    // private $data;
    private $pdo;



    public function __construct(PDO $pdo, string $table = 'listings')
    {
        // $this->data = $data;
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function select(array $params)
    {


        try {
            $baseSelectQuery = "SELECT * FROM {$this->table} ";
            $conditions = [];
            $bindings = [];


            if (!empty($params['rooms'])) {
                $conditions[] = "rooms = :rooms";
                $bindings[':rooms'] = $params['rooms'];
            }
            if (!empty($params['district'])) {
                $conditions[] = "district = :district";
                $bindings[':district'] = $params['district'];
            }
            if (!empty($params['m2_min'])) {
                $conditions[] = "m2 > :m2_min";
                $bindings[':m2_min'] = $params['m2_min'];
            }
            if (!empty($params['m2_max'])) {
                $conditions[] = "m2 < :m2_max";
                $bindings[':m2_max'] = $params['m2_max'];
            }
            if (!empty($params['price_min'])) {
                $price_min = preg_replace('/[^\d.]/', '', $params['price_min']);
                $conditions[] = "price > :price_min";
                $bindings[':price_min'] = $price_min;
            }
            if (!empty($params['price_max'])) {
                $price_max = preg_replace('/[^\d.]/', '', $params['price_max']);
                $conditions[] = "price < :price_max";
                $bindings[':price_max'] = $price_max;
            }
            if (!empty($params['floor_min'])) {
                $conditions[] = "substr(floor, 1, instr(floor, '/') - 1) >= :floor_min";
                $bindings[':floor_min'] = $params['floor_min'];
            }
            if (!empty($params['floor_max'])) {
                $conditions[] = "substr(floor, 1, instr(floor, '/') - 1) <= :floor_max";
                $bindings[':floor_max'] = $params['floor_max'];
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
            // die('Error creating table: ' . $e->getMessage());
            throw new RuntimeException("Query failed: " . $e->getMessage());
        }
    }


    public function insertInDb(array $data)
    {

        try {

            $query = "INSERT INTO " . $this->table . " (title, imgSrc, district, floor, series, price, m2, rooms, street, pubDate, link, hash) VALUES (:title, :imgSrc, :district, :floor, :series, :price, :m2, :rooms, :street, :pubDate, :link, :hash);";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(":title", $data['title']);
            $stmt->bindParam(":imgSrc", $data['imgSrc']);
            $stmt->bindParam(":district", $data['pagasts']);
            $stmt->bindParam(":floor", $data['stavs']);
            $stmt->bindParam(":series", $data['serija']);
            $stmt->bindParam(":price", $data['cena']);
            $stmt->bindParam(":m2", $data['m2']);
            $stmt->bindParam(":rooms", $data['istabas']);
            $stmt->bindParam(":street", $data['iela']);
            $stmt->bindParam(":pubDate", $data['pubDate']);
            $stmt->bindParam(":link", $data['link']);
            $stmt->bindParam(":hash", $data['hash']);
            $stmt->execute();
        } catch (PDOException $e) {
            $errorCode = $e->getCode();

            if ($errorCode === '23000' || $errorCode === 23000) {
                echo 'Entry with this hash already exists in DB';
            } else {

                // die("Query failed: " . $e->getMessage());
                throw new RuntimeException("Query failed: " . $e->getMessage());
            }
        }
    }
}
