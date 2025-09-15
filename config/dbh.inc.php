<?php
require '/var/www/html/vendor/autoload.php';

// Use environment variables set by Docker
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_DATABASE') ?: 'apartmentsearchdb';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'password';

try {
    // Step 1: Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Step 2: Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Step 3: Connect to the newly created database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Step 4: Return PDO object for use
    return $pdo;
} catch (PDOException $e) {
    die("MySQL connection failed: " . $e->getMessage());
}
