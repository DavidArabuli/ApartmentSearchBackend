<?php
require_once 'dbh.inc.php';
class Selector
{
    public $pdo;


    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function mainSelector(array $params)
    {


        try {
            $baseSelectQuery = "SELECT * FROM listings ";
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
}
