<?php
require '/var/www/html/vendor/autoload.php';

// environment variables for Docker
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_DATABASE') ?: 'apartmentsearchdb';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'password';

try {

    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);


    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");


    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);


    return $pdo;
} catch (PDOException $e) {
    die("MySQL connection failed: " . $e->getMessage());
}
