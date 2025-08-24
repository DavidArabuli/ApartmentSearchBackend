<?php

require_once __DIR__ . '/../config/dbh.inc.php';

$sql = "CREATE TABLE sent_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    favorite_id INT NOT NULL,
    listing_link VARCHAR(500) NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (favorite_id, listing_link),
    FOREIGN KEY (favorite_id) REFERENCES favorites(id)
);";

try {
    $pdo->exec($sql);
    echo "Table created";
} catch (PDOException $e) {
    die('Error creating listings table: ' . $e->getMessage());
}
