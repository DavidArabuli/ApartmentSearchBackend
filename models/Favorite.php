<?php

class Favorite
{
    private $table;
    private $pdo;



    public function __construct(PDO $pdo, string $table = 'favorites')
    {

        $this->pdo = $pdo;
        $this->table = $table;
    }
    public function getAll(): array
    {
        try {
            $query = "SELECT * FROM {$this->table}";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to fetch favorites: " . $e->getMessage());
        }
    }

    public function select(array $params)
    {


        try {
            $baseSelectQuery = "SELECT * FROM {$this->table} ";
            $conditions = [];
            $bindings = [];

            if (!empty($params['id'])) {
                $conditions[] = "id = :id";
                $bindings[':id'] = $params['id'];
            }
            if (!empty($params['email'])) {
                $conditions[] = "email = :email";
                $bindings[':email'] = $params['email'];
            }

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


            $stmt = $this->pdo->prepare($baseSelectQuery);
            $stmt->execute($bindings);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {

            throw new RuntimeException("Query failed: " . $e->getMessage());
        }
    }
    private function generateHash(array $data): string
    {

        ksort($data);


        $hashData = [
            'email' => $data['email'] ?? '',
            'district' => $data['district'] ?? '',
            'floor_min' => $data['floor_min'] ?? '',
            'floor_max' => $data['floor_max'] ?? '',
            'series' => $data['series'] ?? '',
            'price_min' => $data['price_min'] ?? '',
            'price_max' => $data['price_max'] ?? '',
            'm2_min' => $data['m2_min'] ?? '',
            'm2_max' => $data['m2_max'] ?? '',
            'rooms' => $data['rooms'] ?? '',
            'street' => $data['street'] ?? '',
        ];

        return substr(md5(json_encode($hashData)), 0, 8);
    }
    public function insertInDb(array $data)
    {
        $data['hash'] = $this->generateHash($data);
        try {

            $query = "INSERT INTO " . $this->table . " 
            (email, district, floor_min, floor_max, series, price_min, price_max, m2_min, m2_max, rooms, street, hash) 
            VALUES 
            (:email, :district, :floor_min, :floor_max, :series, :price_min, :price_max, :m2_min, :m2_max, :rooms, :street, :hash);";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(":district", $data['district']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":floor_min", $data['floor_min']);
            $stmt->bindParam(":floor_max", $data['floor_max']);
            $stmt->bindParam(":series", $data['series']);
            $stmt->bindParam(":price_min", $data['price_min']);
            $stmt->bindParam(":price_max", $data['price_max']);
            $stmt->bindParam(":m2_min", $data['m2_min']);
            $stmt->bindParam(":m2_max", $data['m2_max']);
            $stmt->bindParam(":rooms", $data['rooms']);
            $stmt->bindParam(":street", $data['street']);
            $stmt->bindParam(":hash", $data['hash']);
            $stmt->execute();
        } catch (PDOException $e) {

            $errorInfo = $e->errorInfo;

            if ($e->getCode() === '23000' && isset($errorInfo[1]) && $errorInfo[1] == 1062) {
                http_response_code(409);
                header('Content-Type: application/json');
                echo json_encode([
                    "error" => true,
                    "duplicate" => true,
                    "message" => "This favorite already exists."
                ]);
            } else {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode([
                    "error" => true,
                    "message" => $e->getMessage()
                ]);
            }
            exit;
        }
    }
}
