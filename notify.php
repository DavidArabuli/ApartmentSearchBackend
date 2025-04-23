<?php
require_once 'dbh.inc.php';

class Notifier
{

    public $params;

    public function __construct($params)
    {
        $this->params = $params;
        
    }
    public function insertInDb()
    {
        require_once "dbh.inc.php";
        global $pdo;
        try {

            $query = "INSERT INTO orders (email, params) VALUES (:email, :params);";

            $stmt = $pdo->prepare($query);

            $stmt->bindParam(":email", $this->title);
            $stmt->bindParam(":params", $this->params);
            $stmt->bindParam(":pagasts", $this->pagasts);
            $stmt->bindParam(":stavs", $this->stavs);
            $stmt->bindParam(":serija", $this->serija);
            $stmt->bindParam(":cena", $this->cena);
            $stmt->bindParam(":m2", $this->m2);
            $stmt->bindParam(":istabas", $this->istabas);
            $stmt->bindParam(":iela", $this->iela);
            $stmt->bindParam(":pubDate", $this->pubDate);
            $stmt->bindParam(":link", $this->link);
            $stmt->bindParam(":hash", $this->hash);
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
