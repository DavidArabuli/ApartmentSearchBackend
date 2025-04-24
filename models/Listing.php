<?php
// echo "Hello from insert";

class Listing
{
    private const TABLE = 'listings';
    // private $title;
    // private $imgSrc;
    // private $pagasts;
    // private $stavs;
    // private $serija;
    // private $cena;
    // private $m2;
    // private $istabas;
    // private $iela;
    // private $pubDate;
    // private $link;
    // private $hash;
    private $data;
    private $pdo;


    // public function __construct($title, $imgSrc, $pagasts, $stavs, $serija, $cena, $m2, $istabas, $iela, $pubDate, $link, $hash)
    public function __construct(array $data, PDO $pdo)
    {
        $this->data = $data;
        $this->pdo = $pdo;
        // $this->title = $title;
        // $this->imgSrc = $imgSrc;
        // $this->pagasts = $pagasts;
        // $this->stavs = $stavs;
        // $this->serija = $serija;
        // $this->cena = $cena;
        // $this->m2 = $m2;
        // $this->istabas = $istabas;
        // $this->iela = $iela;
        // $this->pubDate = $pubDate;
        // $this->link = $link;
        // $this->hash = $hash;
    }
    public function insertInDb()
    {
        // require_once "dbh.inc.php";
        require_once __DIR__ . '/../config/dbh.inc.php';

        // global $pdo;
        try {

            $query = "INSERT INTO " . self::TABLE . " (title, imgSrc, district, floor, series, price, m2, rooms, street, pubDate, link, hash) VALUES (:title, :imgSrc, :pagasts, :stavs, :serija, :cena, :m2, :istabas, :iela, :pubDate, :link, :hash);";

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
