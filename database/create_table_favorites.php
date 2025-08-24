<?php

require_once __DIR__ . '/../config/dbh.inc.php';


$sql = "CREATE TABLE IF NOT EXISTS favorites (
id INT AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(50),
district VARCHAR(100),
street VARCHAR(150),
rooms TINYINT,
m2_min FLOAT,
m2_max FLOAT,
floor_min TINYINT,
floor_max TINYINT,
series VARCHAR(50),
price_min INT,
price_max INT,
hash CHAR(8) UNIQUE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

try {
    $pdo->exec($sql);
    echo "Table created";
} catch (PDOException $e) {
    die('Error creating Favorites table: ' . $e->getMessage());
}
