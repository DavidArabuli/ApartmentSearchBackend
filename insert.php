<?php
echo "Hello from insert";

class DbHandler
{
    private $title;
    private $imgSrc;
    private $pagasts;
    private $stavs;
    private $serija;
    private $cena;
    private $m2;
    private $istabas;
    private $iela;
    private $pubDate;
    private $link;
    private $hash;


    public function __construct($title, $imgSrc, $pagasts, $stavs, $serija, $cena, $m2, $istabas, $iela, $pubDate, $link, $hash)
    {
        $this->title = $title;
        $this->imgSrc = $imgSrc;
        $this->pagasts = $pagasts;
        $this->stavs = $stavs;
        $this->serija = $serija;
        $this->cena = $cena;
        $this->m2 = $m2;
        $this->istabas = $istabas;
        $this->iela = $iela;
        $this->pubDate = $pubDate;
        $this->link = $link;
        $this->hash = $hash;
    }
    public function insertInDb()
    {
        require_once "dbh.inc.php";
        global $pdo;
        try {

            $query = "INSERT INTO ss_rss_riga (title, imgSrc, pagasts, stavs, serija, cena, m2, istabas, iela, pub_date, link, hash) VALUES (:title, :imgSrc, :pagasts, :stavs, :serija, :cena, :m2, :istabas, :iela, :pubDate, :link, :hash);";

            $stmt = $pdo->prepare($query);

            $stmt->bindParam(":title", $this->title);
            $stmt->bindParam(":imgSrc", $this->imgSrc);
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
