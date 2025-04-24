<?php

require_once __DIR__ . '/../config/dbh.inc.php';


$sql = "CREATE TABLE IF NOT EXISTS listings (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255),
link TEXT,
pubDate DATETIME,
imgSrc TEXT,
district VARCHAR(100),
street VARCHAR(150),
rooms TINYINT,
m2 FLOAT,
floor VARCHAR(10),
series VARCHAR(50),
price INT,
hash CHAR(8) UNIQUE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

try {
    $pdo->exec($sql);
    echo "Table created";
} catch (PDOException $e) {
    die('Error creating table: ' . $e->getMessage());
}
