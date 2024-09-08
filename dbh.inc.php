<?php
try {
    $pdo = new PDO('sqlite:C:/xampp/htdocs/dashboard/ssParser/ss_rss.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // var_dump($pdo);
} catch (PDOException $e) {
    echo "Connection failed " . $e->getMessage();
}
